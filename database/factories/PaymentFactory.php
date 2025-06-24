<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'payment_method' => fake()->randomElement(['credit_card', 'paypal', 'bank_transfer', 'cash']),
            'amount_paid' => fake()->randomFloat(2, 100, 2000),
            'payment_date' => fake()->dateTimeThisYear(),
            'status' => fake()->randomElement(['paid', 'failed', 'refunded']),
            'transaction_id' => fake()->uuid(),
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'paid',
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
        ]);
    }
}
