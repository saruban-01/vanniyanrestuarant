<?php

use App\Models\RestaurantSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Analytics & Marketing settings stored through the central RestaurantSetting system.
     * Keys are created once; the admin panel manages their values. Nothing is hard-coded
     * into environments, so GTM/Pixel IDs can be managed without a redeploy.
     */
    public function up(): void
    {
        $defaults = [
            'analytics_gtm_enabled' => '0',
            'analytics_gtm_container_id' => '',
            'analytics_meta_enabled' => '0',
            'analytics_meta_pixel_id' => '',
            'analytics_events_enabled' => '0',
            'analytics_consent_enabled' => '0',
            'analytics_test_mode' => '0',
            'track_menu' => '0',
            'track_cart' => '0',
            'track_orders' => '0',
            'track_table_bookings' => '0',
            'track_venue_bookings' => '0',
            'track_offers' => '0',
            'track_stories' => '0',
            'track_google_reviews' => '0',
        ];

        foreach ($defaults as $key => $value) {
            RestaurantSetting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    public function down(): void
    {
        RestaurantSetting::whereIn('key', [
            'analytics_gtm_enabled',
            'analytics_gtm_container_id',
            'analytics_meta_enabled',
            'analytics_meta_pixel_id',
            'analytics_events_enabled',
            'analytics_consent_enabled',
            'analytics_test_mode',
            'track_menu',
            'track_cart',
            'track_orders',
            'track_table_bookings',
            'track_venue_bookings',
            'track_offers',
            'track_stories',
            'track_google_reviews',
        ])->delete();
    }
};