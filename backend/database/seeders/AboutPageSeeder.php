<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AboutPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('about_pages')->insert([
            'hero_eyebrow' => 'Est. 2020',
            'hero_title' => 'Our Story',
            'hero_description' => 'We started with a simple belief: online shopping should be effortless, joyful, and built on trust. Six years later, that belief powers everything we do.',
            'mission_eyebrow' => 'Our Mission',
            'mission_title' => 'Making great products accessible to everyone, everywhere',
            'mission_description' => 'We believe that everyone deserves access to high-quality products at fair prices. Our mission is to bridge the gap between makers and customers, creating a marketplace where quality, transparency, and value come together.

From our warehouse to your doorstep, every step is designed with care. We partner with ethical suppliers, invest in sustainable practices, and put our customers at the heart of every decision.',
            'mission_image' => 'about/pexels-photo-3184360.avif',
            'values_eyebrow' => 'What We Stand For',
            'values_title' => 'Our Core Values',
            'values_description' => 'These principles shape our culture, guide our decisions, and define how we serve our community.',
            'team_eyebrow' => 'Meet the Team',
            'team_title' => 'The People Behind Shoply',
            'team_description' => 'A diverse team of dreamers, builders, and problem-solvers working to make shopping better for everyone.',
            'milestones_eyebrow' => 'Milestones',
            'milestones_title' => 'Our Journey So Far',
            'milestones_description' => 'From a garage startup to a global marketplace, here are the moments that defined us.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
