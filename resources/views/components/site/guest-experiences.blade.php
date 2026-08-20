@props([
    'enabled' => false,
    'data' => null,
    'heading' => 'Loved by our guests',
    'subtitle' => 'Real experiences from people who have visited Vanniyan Restaurant.',
    'readUrl' => null,
    'writeUrl' => null,
])

@if ($enabled)
@php
    $reviews = collect($data['reviews'] ?? [])->values();
    $hasRating = (float) ($data['rating'] ?? 0) > 0;
    $showWrite = (bool) $writeUrl;
    $showRead = (bool) ($readUrl ?: ($data['maps_url'] ?? null));
    $fallbackUrl = $readUrl ?: ($data['maps_url'] ?? null);
@endphp

<section class="py-24 bg-vanniyan-white" aria-labelledby="guest-experiences-heading">
    <div class="max-w-[1280px] mx-auto px-6 md:px-12">
        <div class="text-center">
            <span class="block text-vanniyan-gold font-bold tracking-[0.2em] uppercase text-xs mb-3">Guest Experiences</span>
            <h2 id="guest-experiences-heading" class="text-3xl md:text-4xl font-serif font-bold text-vanniyan-green-900">{{ $heading }}</h2>
            <div class="w-12 h-px bg-vanniyan-gold mx-auto mt-5"></div>
            <p class="mt-5 text-gray-600 text-lg max-w-2xl mx-auto">{{ $subtitle }}</p>
        </div>

        @if ($data && ! $reviews->isEmpty())
            @if ($hasRating)
                {{-- Rating summary (live Google data only) --}}
                <div class="mt-12 text-center">
                    <p class="text-5xl font-serif font-bold text-vanniyan-green-900">
                        {{ number_format((float) $data['rating'], 1) }} <span class="text-vanniyan-gold text-4xl">★</span>
                    </p>
                    <p class="mt-2 text-gray-500 text-sm">
                        {{ number_format((int) $data['user_rating_count']) }} Google reviews
                    </p>
                    <p class="sr-only">Rated {{ number_format((float) $data['rating'], 1) }} out of 5 stars from {{ number_format((int) $data['user_rating_count']) }} Google reviews</p>
                </div>
            @endif

            {{-- Review cards --}}
            <div class="mt-14 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($reviews as $review)
                    <x-site.review-card
                        :author="$review['author']"
                        :rating="$review['rating']"
                        :text="$review['text']"
                        :relativeTime="$review['relative_time']"
                        :url="$review['url'] ?? null"
                    />
                @endforeach
            </div>

            {{-- CTAs --}}
            <div class="mt-14 text-center">
                @if ($showWrite)
                    <a href="{{ $writeUrl }}" target="_blank" rel="noopener noreferrer"
                       data-track-event="google_write_review_clicked"
                       class="inline-flex items-center justify-center min-h-[48px] bg-vanniyan-green-900 text-white hover:bg-vanniyan-gold transition-colors duration-300 font-bold uppercase tracking-wider text-sm rounded-full px-10 py-3.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-green-900"
                       aria-label="Write a review for Vanniyan Restaurant on Google">
                        Write a Google Review
                    </a>
                @endif
                @if ($showRead)
                    <a href="{{ $fallbackUrl }}" target="_blank" rel="noopener noreferrer"
                       data-track-event="google_reviews_clicked"
                       class="inline-flex items-center justify-center min-h-[48px] border border-vanniyan-green-900 text-vanniyan-green-900 hover:bg-vanniyan-green-900 hover:text-white transition-colors duration-300 font-bold uppercase tracking-wider text-sm rounded-full px-10 py-3.5 mt-4 sm:mt-0 sm:ml-4 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-green-900"
                       aria-label="Read all Vanniyan Restaurant reviews on Google">
                        Read All Google Reviews
                    </a>
                @endif
            </div>
        @else
            {{-- Graceful fallback when Google data is unavailable --}}
            <div class="mt-14 text-center">
                <p class="text-gray-700 text-lg max-w-xl mx-auto">
                    See what our guests are saying on Google.
                </p>
                @if ($fallbackUrl)
                    <a href="{{ $fallbackUrl }}" target="_blank" rel="noopener noreferrer"
                       data-track-event="google_reviews_clicked"
                       class="inline-flex items-center justify-center min-h-[48px] bg-vanniyan-green-900 text-white hover:bg-vanniyan-gold transition-colors duration-300 font-bold uppercase tracking-wider text-sm rounded-full px-10 py-3.5 mt-8 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-vanniyan-green-900"
                       aria-label="Read Vanniyan Restaurant reviews on Google">
                        Read Google Reviews
                    </a>
                @endif
            </div>
        @endif
    </div>
</section>
@endif