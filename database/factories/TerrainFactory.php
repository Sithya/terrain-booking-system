<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TerrainFactory extends Factory
{
    public function definition(): array
    {
        return [
            'owner_id' => User::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(3),
            'location' => fake()->address(),
            'area_size' => fake()->randomFloat(2, 100, 10000),
            'price_per_day' => fake()->randomFloat(2, 50, 1000),
            'available_from' => fake()->dateTimeBetween('now', '+1 month'),
            'available_to' => fake()->dateTimeBetween('+1 month', '+1 year'),
            'is_available' => fake()->boolean(80),
            'main_image' => fake()->imageUrl(800, 600, 'nature'),
        ];
    }

    public function unavailable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_available' => false,
        ]);
    }
}
