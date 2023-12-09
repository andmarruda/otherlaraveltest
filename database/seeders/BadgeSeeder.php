<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Badge::insert([
            [
                'name'              => 'Beginner',
                'min_achivement'    => 0
            ],

            [
                'name'              => 'Intermediate',
                'min_achivement'    => 4
            ],

            [
                'name'              => 'Advanced',
                'min_achivement'    => 8
            ],

            [
                'name'              => 'Master',
                'min_achivement'    => 10
            ]
        ]);
    }
}
