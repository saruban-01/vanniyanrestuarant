<?php

namespace App\Http\Controllers;

use App\Models\SeoMetadata;
use App\Models\Story;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function sitemap()
    {
        $urls = [];

        // Static public URLs. A route is excluded only when its SEO
        // metadata explicitly carries a noindex directive.
        $staticRoutes = [
            'home' => '/',
            'menu' => '/menu',
            'offers' => '/offers',
            'stories.index' => '/our-story',
            'reservation' => '/booking/table',
            'booking.selection' => '/booking',
            'venue.booking' => '/booking/venue',
            'contact' => '/contact',
            'privacy-policy' => '/privacy-policy',
            'terms-and-conditions' => '/terms-and-conditions',
            'sitemap.page' => '/sitemap',
        ];

        foreach ($staticRoutes as $route => $path) {
            $metadata = SeoMetadata::where('route_name', $route)->first();
            if (! $metadata || ! str_contains($metadata->robots, 'noindex')) {
                $urls[] = [
                    'loc' => url($path),
                    'lastmod' => now()->startOfDay()->toAtomString(),
                    'changefreq' => 'daily',
                    'priority' => $path === '/' ? '1.0' : '0.8',
                ];
            }
        }

        // Published, indexable stories.
        $stories = Story::where('is_published', true)->get();
        foreach ($stories as $story) {
            $seo = $story->seoMetadata;
            if (! $seo || ! str_contains($seo->robots, 'noindex')) {
                $urls[] = [
                    'loc' => route('our-stories.show', $story->slug),
                    'lastmod' => $story->updated_at->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.6',
                ];
            }
        }

        return response()->view('seo.sitemap', ['urls' => $urls])->header('Content-Type', 'text/xml');
    }

    public function robots()
    {
        $adminPath = trim(config('admin.path'), '/');

        $content = "User-agent: *\n";
        $content .= "Disallow: /{$adminPath}\n";
        $content .= "Disallow: /admin\n";
        $content .= "Disallow: /cart\n";
        $content .= "Disallow: /checkout\n";
        $content .= "Disallow: /order/\n";
        $content .= "Disallow: /booking/venue/\n";
        $content .= "\nSitemap: " . $this->sitemapUrl() . "\n";

        return response($content)->header('Content-Type', 'text/plain');
    }

    /**
     * The production sitemap URL. Prefers the configured canonical base
     * (SEO settings) and falls back to the application URL.
     */
    private function sitemapUrl(): string
    {
        $canonicalBase = \App\Models\RestaurantSetting::where('key', 'seo_canonical_base')->value('value');

        if ($canonicalBase && str_starts_with($canonicalBase, 'http')) {
            return rtrim($canonicalBase, '/').'/sitemap.xml';
        }

        return rtrim(config('app.url'), '/').'/sitemap.xml';
    }
}