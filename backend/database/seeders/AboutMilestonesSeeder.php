<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AboutMilestonesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('about_milestones')->insert([
            [
                'year' => 2020,
                'title' => 'Founded',
                'description' => 'Started in a small garage with a big dream and three passionate founders.',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'year' => 2021,
                'title' => '10K Customers',
                'description' => 'Reached our first major milestone of 10,000 happy customers in year one.',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'year' => 2022,
                'title' => 'International',
                'description' => 'Expanded shipping to 40+ countries, bringing our products to a global audience.',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'year' => 2023,
                'title' => '50K Products',
                'description' => 'Crossed 50,000 curated products across hundreds of categories.',
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'year' => 2024,
                'title' => 'Mobile App',
                'description' => 'Launched our award-winning mobile app for iOS and Android with 4.9-star rating.',
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'year' => 2025,
                'title' => 'AI Features',
                'description' => 'Introduced AI-powered recommendations, search, and personalized shopping experiences.',
                'sort_order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
