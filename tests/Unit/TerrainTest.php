<?php

use App\Models\Terrain;
use App\Models\User;

test('terrain belongs to an owner', function () {
    $user = User::factory()->create();
    $terrain = Terrain::factory()->create(['owner_id' => $user->id]);

    expect($terrain->owner)->toBeInstanceOf(User::class);
    expect($terrain->owner->id)->toBe($user->id);
});

test('terrain calculates average rating correctly', function () {
    $terrain = Terrain::factory()->create();
    
    // Create reviews with ratings: 4, 5, 3 (average = 4)
    $terrain->reviews()->create([
        'user_id' => User::factory()->create()->id,
        'rating' => 4,
        'comment' => 'Good',
    ]);
    
    $terrain->reviews()->create([
        'user_id' => User::factory()->create()->id,
        'rating' => 5,
        'comment' => 'Excellent',
    ]);
    
    $terrain->reviews()->create([
        'user_id' => User::factory()->create()->id,
        'rating' => 3,
        'comment' => 'Average',
    ]);

    expect($terrain->average_rating)->toBe('4.0000'); // Expect string from DB
});

test('terrain counts total reviews correctly', function () {
    $terrain = Terrain::factory()->create();
    
    $terrain->reviews()->createMany([
        ['user_id' => User::factory()->create()->id, 'rating' => 5],
        ['user_id' => User::factory()->create()->id, 'rating' => 4],
        ['user_id' => User::factory()->create()->id, 'rating' => 3],
    ]);

    expect($terrain->total_reviews)->toBe(3);
});
