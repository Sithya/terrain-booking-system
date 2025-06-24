<?php

use App\Models\Booking;
use Carbon\Carbon;

test('booking calculates duration correctly', function () {
    $booking = Booking::factory()->create([
        'start_date' => Carbon::parse('2024-01-01'),
        'end_date' => Carbon::parse('2024-01-05'),
    ]);

    expect($booking->duration)->toBe(5.0); // Expect float, not int
});

test('booking duration calculation for single day', function () {
    $booking = Booking::factory()->create([
        'start_date' => Carbon::parse('2024-01-01'),
        'end_date' => Carbon::parse('2024-01-01'),
    ]);

    expect($booking->duration)->toBe(1.0); // Expect float, not int
});
