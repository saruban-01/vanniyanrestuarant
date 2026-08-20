<?php

namespace App\Services;

use App\Models\SeoMetadata;
use App\Models\RestaurantSetting;
use Illuminate\Support\Facades\Request;

class SeoService
{
    /**
     * Get SEO metadata for a specific route or model.
     * Falls back to global defaults if no specific data exists.
     */
    public static function getMetadata($model = null, $routeName = null)
    {
        $metadata = null;

        if ($model && method_exists($model, 'seoMetadata')) {
            $metadata = $model->seoMetadata;
        } elseif ($routeName) {
            $metadata = SeoMetadata::where('route_name', $routeName)->first();
        }

        $globalTitle = RestaurantSetting::where('key', 'seo_default_title')->value('value') ?? 'Vanniyan Restaurant';
        $globalDesc = RestaurantSetting::where('key', 'seo_default_description')->value('value') ?? 'Experience the Royal Taste of Vanni at Vanniyan Restaurant in Kilinochchi.';
        $globalImage = RestaurantSetting::where('key', 'seo_default_og_image')->value('value');
        $canonicalBase = RestaurantSetting::where('key', 'seo_canonical_base')->value('value') ?? config('app.url');

        // Dynamic Fallbacks for Models if metadata is missing or fields are null
        $dynamicTitle = null;
        $dynamicDesc = null;
        $dynamicImage = null;

        if ($model) {
            $dynamicTitle = $model->title ?? $model->name ?? null;
            if ($dynamicTitle) {
                $dynamicTitle .= ' | ' . $globalTitle;
            }
            
            $dynamicDesc = $model->short_description ?? $model->excerpt ?? $model->description ?? null;
            if ($dynamicDesc) {
                $dynamicDesc = \Illuminate\Support\Str::limit(strip_tags($dynamicDesc), 160);
            }
            
            $dynamicImage = $model->og_image ?? $model->image ?? $model->hero_image ?? null;
            if ($dynamicImage && !str_starts_with($dynamicImage, 'http')) {
                $dynamicImage = asset('storage/' . $dynamicImage);
            }
        }

        // Title logic
        $title = $metadata?->meta_title ?: ($dynamicTitle ?: $globalTitle);
        // If it's a static route and meta_title is null, try to generate a sensible one
        if (!$model && !$metadata?->meta_title && $routeName) {
            $pageNames = [
                'home' => 'Vanniyan Restaurant | The Royal Taste of Vanni | Kilinochchi',
                'menu' => 'Vanniyan Restaurant Menu | Kilinochchi',
                'offers' => 'Vanniyan Restaurant Our Deals | Kilinochchi',
                'events.index' => 'Vanniyan Restaurant Events | Kilinochchi',
                'stories.index' => 'Our Story | Vanniyan Restaurant',
                'reservation' => 'Reserve a Table at Vanniyan Restaurant | Kilinochchi',
                'contact' => 'Contact Vanniyan Restaurant | Location & Opening Hours | Kilinochchi',
                'privacy-policy' => 'Vanniyan Restaurant Privacy Policy',
                'terms-and-conditions' => 'Vanniyan Restaurant Terms & Conditions',
                'sitemap.page' => 'Vanniyan Restaurant Sitemap',
            ];
            if (isset($pageNames[$routeName])) {
                $title = $pageNames[$routeName];
            }
        }

        $description = $metadata?->meta_description ?: ($dynamicDesc ?: $globalDesc);
        
        $currentUrl = Request::url();
        // Remove query parameters for canonical by default, unless it's configured differently
        $canonicalUrl = $metadata?->canonical_url ?: $currentUrl;
        
        // Ensure canonical starts with canonical base if set
        if ($canonicalBase && str_starts_with($canonicalUrl, config('app.url'))) {
             $canonicalUrl = str_replace(config('app.url'), rtrim($canonicalBase, '/'), $canonicalUrl);
        }

        $ogImage = $metadata?->og_image;
        if ($ogImage && !str_starts_with($ogImage, 'http')) {
            $ogImage = asset('storage/' . $ogImage);
        }
        $ogImage = $ogImage ?: ($dynamicImage ?: ($globalImage ? asset('storage/' . $globalImage) : null));

        return [
            'meta_title' => $title,
            'meta_description' => $description,
            'canonical_url' => $canonicalUrl,
            'og_title' => $metadata?->og_title ?: $title,
            'og_description' => $metadata?->og_description ?: $description,
            'og_image' => $ogImage,
            'robots' => $metadata?->robots ?: 'index, follow',
            'schema_type' => $metadata?->schema_type,
        ];
    }
}
