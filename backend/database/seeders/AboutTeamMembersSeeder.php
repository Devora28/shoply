<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AboutTeamMembersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('about_team_members')->insert([
            [
                'name' => 'Jordan Carter',
                'role' => 'CEO & Co-Founder',
                'avatar' => 'avatars/200.jpg',
                'twitter_url' => null,
                'linkedin_url' => null,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Priya Sharma',
                'role' => 'CTO & Co-Founder',
                'avatar' => 'avatars/200 (1).jpg',
                'twitter_url' => null,
                'linkedin_url' => null,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Marcus Lee',
                'role' => 'Head of Design',
                'avatar' => 'avatars/200 (2).jpg',
                'twitter_url' => null,
                'linkedin_url' => null,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Elena Rodriguez',
                'role' => 'VP of Operations',
                'avatar' => 'avatars/200 (3).jpg',
                'twitter_url' => null,
                'linkedin_url' => null,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
