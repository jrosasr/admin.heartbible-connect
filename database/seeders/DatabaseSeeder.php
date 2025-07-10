<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Story;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Story::create(
            [
                'title' => 'El buen samaritano',
                'verses_count' => 15,
                'location' => 'Lucas 10:20-30',
                'difficulty' => 'low'
            ]
        );
    }
}
