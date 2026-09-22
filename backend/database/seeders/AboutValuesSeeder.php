<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AboutValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('about_values')->insert([
            [
                'title' => 'Quality',
                'description' => 'We obsess over every detail to deliver products that exceed expectations and stand the test of time.',
                'icon' => 'award',
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Trust',
                'description' => 'Transparency and honesty guide everything we do, from sourcing to pricing to customer support.',
                'icon' => 'shield-check',
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Innovation',
                'description' => 'We constantly explore new technologies and ideas to make shopping smarter, faster, and more enjoyable.',
                'icon' => 'lightbulb',
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Customer First',
                'description' => 'Every decision starts with the question: does this make life better for our customers?',
                'icon' => 'heart',
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Sustainability',
                'description' => 'We are committed to eco-friendly packaging, ethical sourcing, and reducing our carbon footprint.',
                'icon' => 'leaf',
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Community',
                'description' => 'We give back through local partnerships, charitable initiatives, and supporting small creators.',
                'icon' => 'users',
                'sort_order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
