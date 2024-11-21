<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'content' => fake()->paragraph(2),
            'platforms' => ['linkedin', 'reddit'],
            'status' => fake()->randomElement(['draft', 'queued', 'published']),
            'media_urls' => null,
            'platform_post_ids' => null,
            'published_at' => null,
        ];
    }

    public function published(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'published',
                'published_at' => now(),
                'platform_post_ids' => [
                    'linkedin' => 'post_' . fake()->uuid(),
                    'reddit' => 't3_' . Str::random(6),
                ],
            ];
        });
    }

    public function queued(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'queued',
            ];
        });
    }
}
