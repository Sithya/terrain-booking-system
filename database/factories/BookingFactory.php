<?php

namespace Database\Factories;

use App\Models\Terrain;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 month', '+1 month');
        $endDate = fake()->dateTimeBetween($startDate, $startDate->format('Y-m-d') . ' +2 weeks');
        $duration = $startDate->diff($endDate)->days + 1;
        $pricePerDay = fake()->randomFloat(2, 50, 500);

        return [
            'terrain_id' => Terrain::factory(),
            'renter_id' => User::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_price' => $pricePerDay * $duration,
            'status' => fake()->randomElement(['pending', 'approved', 'rejected', 'cancelled', 'completed']),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }
}
