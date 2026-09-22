<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'site_name' => 'Shoply',
            'contact_email' => 'support@shoply.com',
            'contact_phone' => '+1 800 555 0100',
            'address_line' => '123 Commerce St',
            'city' => 'Portland',
            'state' => 'OR',
            'postal_code' => '97201',
            'country' => 'USA',
            'weekday_open' => '09:00',
            'weekday_close' => '18:00',
            'saturday_open' => '10:00',
            'saturday_close' => '16:00',
            'socials' => [
                [
                    'name' => 'X',
                    'url' => null,
                    'icon' => 'simple-icons:x',
                ],
                [
                    'name' => 'Instagram',
                    'url' => null,
                    'icon' => 'simple-icons:instagram',
                ],
                [
                    'name' => 'LinkedIn',
                    'url' => null,
                    'icon' => 'simple-icons:linkedin',
                ],
                [
                    'name' => 'Facebook',
                    'url' => null,
                    'icon' => 'simple-icons:facebook',
                ],
            ],
        ]);
    }
}
