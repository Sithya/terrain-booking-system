<?php

use App\Models\Booking;
use App\Models\Terrain;
use App\Models\User;

test('users cannot book their own terrains', function () {
    $user = User::factory()->create();
    $terrain = Terrain::factory()->create(['owner_id' => $user->id]);

    $this->actingAs($user);

    $bookingData = [
        'terrain_id' => $terrain->id,
        'start_date' => now()->addDays(1)->format('Y-m-d'),
        'end_date' => now()->addDays(3)->format('Y-m-d'),
    ];

    $response = $this->postJson('/bookings', $bookingData);

    $response->assertForbidden();
});

test('users can book available terrains', function () {
    $renter = User::factory()->create();
    $owner = User::factory()->create();
    $terrain = Terrain::factory()->create([
        'owner_id' => $owner->id,
        'price_per_day' => 100.00,
    ]);

    $this->actingAs($renter);

    $bookingData = [
        'terrain_id' => $terrain->id,
        'start_date' => now()->addDays(1)->format('Y-m-d'),
        'end_date' => now()->addDays(3)->format('Y-m-d'),
    ];

    $response = $this->postJson('/bookings', $bookingData);

    $this->assertDatabaseHas('bookings', [
        'terrain_id' => $terrain->id,
        'renter_id' => $renter->id,
        'total_price' => 300.00, // 3 days * $100
        'status' => 'pending',
    ]);

    $response->assertStatus(201);
});

test('terrain owners can approve bookings', function () {
    $owner = User::factory()->create();
    $renter = User::factory()->create();
    $terrain = Terrain::factory()->create(['owner_id' => $owner->id]);
    $booking = Booking::factory()->create([
        'terrain_id' => $terrain->id,
        'renter_id' => $renter->id,
        'status' => 'pending',
    ]);

    $this->actingAs($owner);

    $response = $this->putJson("/bookings/{$booking->id}", [
        'status' => 'approved',
    ]);

    $this->assertDatabaseHas('bookings', [
        'id' => $booking->id,
        'status' => 'approved',
    ]);

    $response->assertOk();
});

test('booking calculates total price correctly', function () {
    $terrain = Terrain::factory()->create(['price_per_day' => 50.00]);
    $renter = User::factory()->create();

    $this->actingAs($renter);

    $startDate = now()->addDays(1);
    $endDate = now()->addDays(5); // 5 days total

    $bookingData = [
        'terrain_id' => $terrain->id,
        'start_date' => $startDate->format('Y-m-d'),
        'end_date' => $endDate->format('Y-m-d'),
    ];

    $response = $this->postJson('/bookings', $bookingData);

    $this->assertDatabaseHas('bookings', [
        'terrain_id' => $terrain->id,
        'total_price' => 250.00, // 5 days * $50
    ]);
});
