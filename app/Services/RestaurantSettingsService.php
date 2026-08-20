<?php

namespace App\Services;

use App\Models\RestaurantSetting;
use Illuminate\Support\Facades\Cache;

class RestaurantSettingsService
{
    /**
     * Retrieve all settings as a key-value array.
     */
    public function getAll(): array
    {
        return Cache::remember('restaurant_settings', 3600, function () {
            return RestaurantSetting::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Retrieve a specific setting by key.
     */
    public function get(string $key, $default = null)
    {
        $settings = $this->getAll();
        return $settings[$key] ?? $default;
    }
    
    /**
     * Clear the settings cache. Call this when admin updates settings.
     */
    public function clearCache(): void
    {
        Cache::forget('restaurant_settings');
    }
}
