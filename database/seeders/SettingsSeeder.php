<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'ShopEase'],
            ['key' => 'site_description', 'value' => 'Your Premium eCommerce Solution'],
            ['key' => 'contact_email', 'value' => 'support@shopease.com'],
            ['key' => 'contact_phone', 'value' => '+1 (555) 123-4567'],
            ['key' => 'address', 'value' => '123 E-commerce Street, Shopping District'],
            ['key' => 'facebook_url', 'value' => 'https://facebook.com'],
            ['key' => 'twitter_url', 'value' => 'https://twitter.com'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com'],
            ['key' => 'currency_symbol', 'value' => '₹'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
