<?php

use App\Models\Booking;
use App\Models\Review;
use App\Models\Terrain;
use App\Models\User;

test('users can review terrains they have booked', function () {
    $user = User::factory()->create();
    $owner = User::factory()->create();
    $terrain = Terrain::factory()->create(['owner_id' => $owner->id]);
    
    // Create a completed booking
    Booking::factory()->create([
        'terrain_id' => $terrain->id,
        'renter_id' => $user->id,
        'status' => 'completed',
    ]);

    $this->actingAs($user);

    $reviewData = [
        'terrain_id' => $terrain->id,
        'rating' => 5,
        'comment' => 'Amazing place! Highly recommended.',
    ];

    $response = $this->postJson('/reviews', $reviewData);

    $this->assertDatabaseHas('reviews', [
        'terrain_id' => $terrain->id,
        'user_id' => $user->id,
        'rating' => 5,
    ]);

    $response->assertStatus(201);
});

test('users cannot review terrains they have not booked', function () {
    $user = User::factory()->create();
    $terrain = Terrain::factory()->create();

    $this->actingAs($user);

    $reviewData = [
        'terrain_id' => $terrain->id,
        'rating' => 5,
        'comment' => 'Great place!',
    ];

    $response = $this->postJson('/reviews', $reviewData);

    $response->assertForbidden();
});

test('users cannot review the same terrain twice', function () {
    $user = User::factory()->create();
    $owner = User::factory()->create();
    $terrain = Terrain::factory()->create(['owner_id' => $owner->id]);
    
    // Create completed booking and existing review
    Booking::factory()->create([
        'terrain_id' => $terrain->id,
        'renter_id' => $user->id,
        'status' => 'completed',
    ]);
    
    Review::factory()->create([
        'terrain_id' => $terrain->id,
        'user_id' => $user->id,
    ]);

    $this->actingAs($user);

    $reviewData = [
        'terrain_id' => $terrain->id,
        'rating' => 4,
        'comment' => 'Second review attempt',
    ];

    $response = $this->postJson('/reviews', $reviewData);

    $response->assertForbidden();
});

test('terrain owners cannot review their own terrains', function () {
    $user = User::factory()->create();
    $terrain = Terrain::factory()->create(['owner_id' => $user->id]);

    $this->actingAs($user);

    $reviewData = [
        'terrain_id' => $terrain->id,
        'rating' => 5,
        'comment' => 'My own terrain is great!',
    ];

    $response = $this->postJson('/reviews', $reviewData);

    $response->assertForbidden();
});
