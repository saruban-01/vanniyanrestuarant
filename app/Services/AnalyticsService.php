<?php

namespace App\Services;

use App\Models\RestaurantSetting;
use App\Models\TakeawayOrder;

class AnalyticsService
{
    public const CURRENCY = 'LKR';

    /**
     * Event groups that can be individually toggled from the admin panel.
     */
    public const EVENT_TRACKS = [
        'menu',
        'cart',
        'orders',
        'table_bookings',
        'venue_bookings',
        'offers',
        'stories',
        'google_reviews',
    ];

    /**
     * All settings keys owned by the Analytics & Marketing panel.
     */
    public const SETTING_KEYS = [
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
    ];

    public function __construct(private RestaurantSettingsService $settings)
    {
    }

    public function get(string $key, $default = null)
    {
        return $this->settings->get($key, $default);
    }

    public function gtmEnabled(): bool
    {
        return $this->get('analytics_gtm_enabled') === '1' && $this->gtmContainerId() !== '';
    }

    public function gtmContainerId(): string
    {
        $id = trim((string) $this->get('analytics_gtm_container_id', ''));

        return self::isValidGtmId($id) ? $id : '';
    }

    public function metaEnabled(): bool
    {
        return $this->get('analytics_meta_enabled') === '1' && $this->metaPixelId() !== '';
    }

    public function metaPixelId(): string
    {
        $id = trim((string) $this->get('analytics_meta_pixel_id', ''));

        return self::isValidPixelId($id) ? $id : '';
    }

    public function eventsEnabled(): bool
    {
        return $this->get('analytics_events_enabled') === '1';
    }

    public function consentEnabled(): bool
    {
        return $this->get('analytics_consent_enabled') === '1';
    }

    public function testMode(): bool
    {
        return $this->get('analytics_test_mode') === '1';
    }

    /**
     * True only when the global tracking switch AND the specific event group
     * are both enabled. Conversion one-shot flags are only consumed when this
     * returns true for the relevant group.
     */
    public function eventEnabled(string $track): bool
    {
        if (! in_array($track, self::EVENT_TRACKS, true)) {
            return false;
        }

        return $this->eventsEnabled() && $this->get('track_'.$track) === '1';
    }

    public function environment(): string
    {
        return app()->environment('production') ? 'production' : app()->environment();
    }

    public function isConfigured(): bool
    {
        return $this->gtmEnabled() || $this->metaEnabled();
    }

    public static function isValidGtmId($id): bool
    {
        return is_string($id) && preg_match('/^GTM-[A-Z0-9]{4,15}$/', trim($id)) === 1;
    }

    public static function isValidPixelId($id): bool
    {
        return is_string($id) && preg_match('/^\d{13,17}$/', trim($id)) === 1;
    }

    /**
     * Persist validated analytics settings, clear the settings cache, and
     * return a list of actual changes (masked) for audit logging.
     *
     * @return array<string, array{old: string, new: string}>
     */
    public function saveSettings(array $data): array
    {
        $changed = [];

        foreach ($data as $key => $value) {
            if (! in_array($key, self::SETTING_KEYS, true)) {
                continue;
            }

            $current = (string) $this->get($key, '');
            $new = (string) $value;

            if ($current === $new) {
                continue;
            }

            RestaurantSetting::updateOrCreate(['key' => $key], ['value' => $new]);
            $changed[$key] = [
                'old' => $this->masked($key, $current),
                'new' => $this->masked($key, $new),
            ];
        }

        if ($changed !== []) {
            $this->settings->clearCache();
        }

        return $changed;
    }

    // ------------------------------------------------------------------
    // Server-authoritative one-shot conversion flags
    // ------------------------------------------------------------------

    public function purchaseSent(TakeawayOrder $order): bool
    {
        return (bool) $order->purchase_event_sent;
    }

    public function markPurchaseSent(TakeawayOrder $order): void
    {
        $order->forceFill(['purchase_event_sent' => true])->saveQuietly();
    }

    public function confirmedSent($model): bool
    {
        return (bool) $model->analytics_confirmed_sent;
    }

    public function markConfirmedSent($model): void
    {
        $model->forceFill(['analytics_confirmed_sent' => true])->saveQuietly();
    }

    // ------------------------------------------------------------------

    private function masked(string $key, string $value): string
    {
        if (str_contains($key, 'container_id') || str_contains($key, 'pixel_id')) {
            if ($value === '') {
                return 'not configured';
            }

            return 'configured (…'.substr($value, -4).')';
        }

        return $value === '' ? 'off' : ($value === '1' ? 'on' : $value);
    }
}