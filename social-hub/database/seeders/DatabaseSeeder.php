<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Schedule;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Crear algunos posts de ejemplo
        Post::factory()
            ->count(5)
            ->for($user)
            ->published()
            ->create();

        Post::factory()
            ->count(3)
            ->for($user)
            ->queued()
            ->create()
            ->each(function ($post) {
                $post->queuedPost()->create([
                    'scheduled_for' => now()->addHours(rand(1, 24)),
                    'is_scheduled' => true,
                ]);
            });

        // Crear horarios de ejemplo
        foreach (range(1, 5) as $day) {
            foreach ([9, 12, 15, 18] as $hour) {
                Schedule::create([
                    'user_id' => $user->id,
                    'day_of_week' => $day,
                    'time' => sprintf('%02d:00:00', $hour),
                    'is_active' => true,
                ]);
            }
        }

    }
}