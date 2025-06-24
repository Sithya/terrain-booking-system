<?php

use App\Models\Favorite;
use App\Models\Terrain;
use App\Models\User;

test('users can add terrains to favorites', function () {
    $user = User::factory()->create();
    $terrain = Terrain::factory()->create();

    $this->actingAs($user);

    $response = $this->postJson('/favorites', [
        'terrain_id' => $terrain->id,
    ]);

    $this->assertDatabaseHas('favorites', [
        'user_id' => $user->id,
        'terrain_id' => $terrain->id,
    ]);

    $response->assertStatus(201);
});

test('users can remove terrains from favorites', function () {
    $user = User::factory()->create();
    $terrain = Terrain::factory()->create();
    $favorite = Favorite::factory()->create([
        'user_id' => $user->id,
        'terrain_id' => $terrain->id,
    ]);

    $this->actingAs($user);

    $response = $this->deleteJson("/favorites/{$favorite->id}");

    $this->assertDatabaseMissing('favorites', [
        'id' => $favorite->id,
    ]);

    $response->assertOk();
});

test('users cannot duplicate favorites', function () {
    $user = User::factory()->create();
    $terrain = Terrain::factory()->create();
    
    // Create existing favorite
    Favorite::factory()->create([
        'user_id' => $user->id,
        'terrain_id' => $terrain->id,
    ]);

    $this->actingAs($user);

    $response = $this->postJson('/favorites', [
        'terrain_id' => $terrain->id,
    ]);

    $response->assertStatus(422);
});

test('users can view their favorites list', function () {
    $user = User::factory()->create();
    $terrains = Terrain::factory(3)->create();
    
    foreach ($terrains as $terrain) {
        Favorite::factory()->create([
            'user_id' => $user->id,
            'terrain_id' => $terrain->id,
        ]);
    }

    $this->actingAs($user);

    $response = $this->getJson('/favorites');

    $response->assertOk();
    $response->assertJsonStructure(['favorites']);
});
