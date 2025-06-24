<?php
namespace Database\Factories;

use App\Models\Terrain;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'terrain_id' => Terrain::factory(),
            'user_id' => User::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->paragraph(2),
        ];
    }

    public function excellent(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => 5,
        ]);
    }

    public function poor(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => 1,
        ]);
    }
}
