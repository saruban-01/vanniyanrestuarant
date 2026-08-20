<?php

namespace Database\Seeders;

use App\Models\BusinessHour;
use App\Models\RestaurantSetting;
use Illuminate\Database\Seeder;

class RestaurantSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Settings
        $settings = [
            'name' => 'Vanniyan Restaurant',
            'tagline' => 'The Royal Taste of Vanni',
            'phone' => '+94 77 123 4567',
            'email' => 'hello@vanniyan.lk',
            'address' => '123 Kandy Road',
            'city' => 'Kilinochchi',
            'district' => 'Kilinochchi',
            'province' => 'Northern Province',
            'country' => 'LK',
            'postal_code' => '42000',
            'latitude' => '9.3803',
            'longitude' => '80.3982',
            'maps_url' => 'https://maps.app.goo.gl/Kmo3SomPabUBTPs76',
            'facebook_url' => 'https://facebook.com/vanniyan',
            'instagram_url' => 'https://instagram.com/vanniyan',
        ];

        foreach ($settings as $key => $value) {
            RestaurantSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // 2. Business Hours (0=Sun, 1=Mon, ..., 6=Sat)
        // Open every day 10:30am-11pm.
        for ($i = 0; $i <= 6; $i++) {
            BusinessHour::updateOrCreate(
                ['day_of_week' => $i],
                ['is_closed' => false, 'open_time' => '10:30:00', 'close_time' => '23:00:00']
            );
        }
    }
}
