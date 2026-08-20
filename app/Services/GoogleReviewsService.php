<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Fetches Vanniyan Restaurant's Google Place data (rating, review count,
 * and the reviews Google provides) through the official Google Places API (New).
 *
 * - All credentials live in the environment (.env), never in Blade or JS.
 * - Responses are validated, cached (configurable TTL), and failures degrade
 *   to null so the homepage always renders.
 */
class GoogleReviewsService
{
    private const PLACE_DETAILS_ENDPOINT = 'https://places.googleapis.com/v1/places/';

    private const FIELD_MASK = 'id,displayName,rating,userRatingCount,reviews,googleMapsUri';

    private ?array $config = null;

    /**
     * Return the normalized Google data payload, or null when disabled /
     * unconfigured / unreachable. Never throws. When the live API is
     * unavailable, falls back to the restaurant's own verified reviews
     * from config/curated-reviews.php so the homepage always shows real,
     * attributed guest experiences.
     *
     * @return array{rating: float, user_rating_count: int, reviews: array, maps_url: ?string, source: string}|null
     */
    public function getData(): ?array
    {
        if (! $this->enabled()) {
            return null;
        }

        $placeId = $this->placeId();
        if (! $placeId) {
            return null;
        }

        $ttl = max(1, (int) ($this->cmsValue('google_reviews_cache_minutes') ?? config('services.google.cache_minutes', 1440)));
        $cacheKey = 'google_reviews_data_'.$placeId;

        $cached = Cache::get($cacheKey);
        if (is_array($cached)) {
            return $cached;
        }

        $payload = $this->fetch($placeId);

        if ($payload !== null) {
            Cache::put($cacheKey, $payload, $ttl * 60);

            return $payload;
        }

        return $this->curatedData();
    }

    public function enabled(): bool
    {
        $value = $this->cmsValue('google_reviews_enabled');

        return in_array($value, ['1', 'true', 'on', 1, true], true);
    }

    public function placeId(): ?string
    {
        return config('services.google.place_id')
            ?: $this->cmsValue('google_reviews_place_id')
            ?: null;
    }

    public function reviewsUrl(): ?string
    {
        return config('services.google.reviews_url')
            ?: $this->cmsValue('google_reviews_url')
            ?: null;
    }

    public function writeReviewUrl(): ?string
    {
        return config('services.google.write_review_url')
            ?: $this->cmsValue('google_reviews_write_url')
            ?: null;
    }

    public function displayCount(): int
    {
        return max(1, (int) ($this->cmsValue('google_reviews_count') ?? 3));
    }

    private function cmsValue(string $key): mixed
    {
        if ($this->config === null) {
            $published = app(CmsService::class)->getPublishedContent('global');
            $this->config = $published['content'] ?? [];
        }

        return $this->config[$key] ?? null;
    }

    private function curatedData(): ?array
    {
        $reviews = array_slice(
            config('curated-reviews.reviews', []),
            0,
            $this->displayCount()
        );

        if (empty($reviews)) {
            return null;
        }

        $listingUrl = $this->reviewsUrl();

        return [
            'rating' => 0.0,
            'user_rating_count' => 0,
            'reviews' => array_map(
                fn (array $review) => $review + ['url' => $listingUrl],
                $reviews
            ),
            'maps_url' => $listingUrl,
            'source' => 'curated',
        ];
    }

    private function fetch(string $placeId): ?array
    {
        $apiKey = config('services.google.places_api_key');

        if (! $apiKey) {
            Log::warning('Google Reviews: GOOGLE_MAPS_API_KEY is not configured.');
            return null;
        }

        try {
            $response = Http::timeout(config('services.google.timeout_seconds', 6))
                ->withHeaders([
                    'X-Goog-Api-Key' => $apiKey,
                    'X-Goog-FieldMask' => self::FIELD_MASK,
                    'Accept' => 'application/json',
                ])
                ->get(self::PLACE_DETAILS_ENDPOINT.rawurlencode($placeId), ['languageCode' => 'en'])
                ->throw();

            $data = $response->json();

            if (! is_array($data) || empty($data['id'])) {
                Log::warning('Google Reviews: invalid Places API response structure.');
                return null;
            }

            $rating = (float) ($data['rating'] ?? 0);
            $count = (int) ($data['userRatingCount'] ?? 0);

            $reviews = [];
            foreach (($data['reviews'] ?? []) as $review) {
                $text = trim((string) ($review['text']['text'] ?? $review['originalText']['text'] ?? ''));
                if ($text === '') {
                    continue;
                }

                $reviews[] = [
                    'author' => trim((string) ($review['authorAttribution']['displayName'] ?? 'Google User')),
                    'rating' => (int) ($review['rating'] ?? 5),
                    'text' => mb_substr($text, 0, 5000),
                    'relative_time' => trim((string) ($review['relativePublishTimeDescription'] ?? '')),
                    'url' => $review['googleMapsUri'] ?? $review['authorAttribution']['uri'] ?? null,
                ];
            }

            return [
                'rating' => $rating,
                'user_rating_count' => $count,
                'reviews' => $reviews,
                'maps_url' => $data['googleMapsUri'] ?? null,
                'source' => 'live',
            ];
        } catch (\Throwable $e) {
            Log::warning('Google Reviews: fetch failed: '.$e->getMessage());
            return null;
        }
    }
}