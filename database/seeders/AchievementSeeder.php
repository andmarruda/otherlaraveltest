<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Achievement::insert([
            [
                'model'     => 'App\Models\Lesson',
                'name'      => 'First Lesson Watched',
                'min_count' => 1
            ],

            [
                'model'     => 'App\Models\Lesson',
                'name'      => '5 Lessons Watched',
                'min_count' => 5
            ],

            [
                'model'     => 'App\Models\Lesson',
                'name'      => '10 Lessons Watched',
                'min_count' => 10
            ],

            [
                'model'     => 'App\Models\Lesson',
                'name'      => '25 Lessons Watched',
                'min_count' => 25
            ],

            [
                'model'     => 'App\Models\Lesson',
                'name'      => '50 Lessons Watched',
                'min_count' => 50
            ],

            [
                'model'     => 'App\Models\Comment',
                'name'      => 'First Comment Written',
                'min_count' => 1
            ],

            [
                'model'     => 'App\Models\Comment',
                'name'      => '3 Comments Written',
                'min_count' => 3
            ],

            [
                'model'     => 'App\Models\Comment',
                'name'      => '5 Comments Written',
                'min_count' => 5
            ],

            [
                'model'     => 'App\Models\Comment',
                'name'      => '10 Comments Written',
                'min_count' => 10
            ],

            [
                'model'     => 'App\Models\Comment',
                'name'      => '20 Comments Written',
                'min_count' => 20
            ],
        ]);      
    }
}