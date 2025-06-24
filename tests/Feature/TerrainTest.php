<?php

use App\Models\Terrain;
use App\Models\User;

test('guests can view terrain listings', function () {
    $terrains = Terrain::factory(3)->create();

    $response = $this->getJson('/terrains');

    $response->assertOk();
    $response->assertJsonStructure(['terrains']);
});

test('guests can view a specific terrain', function () {
    $terrain = Terrain::factory()->create();

    $response = $this->getJson("/terrains/{$terrain->id}");

    $response->assertOk();
    $response->assertJsonStructure(['terrain']);
});

test('authenticated users can create terrains', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $terrainData = [
        'title' => 'Beautiful Farmland',
        'description' => 'Perfect for camping and outdoor activities',
        'location' => '123 Country Road, Rural Area',
        'area_size' => 500.50,
        'price_per_day' => 75.00,
        'available_from' => now()->addDays(1)->format('Y-m-d'),
        'available_to' => now()->addMonths(6)->format('Y-m-d'),
        'is_available' => true,
    ];

    $response = $this->postJson('/terrains', $terrainData);

    $this->assertDatabaseHas('terrains', [
        'title' => 'Beautiful Farmland',
        'owner_id' => $user->id,
    ]);

    $response->assertStatus(201);
});

test('terrain owners can update their terrains', function () {
    $user = User::factory()->create();
    $terrain = Terrain::factory()->create(['owner_id' => $user->id]);

    $this->actingAs($user);

    $updateData = [
        'title' => 'Updated Terrain Title',
        'price_per_day' => 100.00,
    ];

    $response = $this->putJson("/terrains/{$terrain->id}", $updateData);

    $this->assertDatabaseHas('terrains', [
        'id' => $terrain->id,
        'title' => 'Updated Terrain Title',
        'price_per_day' => 100.00,
    ]);

    $response->assertOk();
});

test('only terrain owners can delete their terrains', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $terrain = Terrain::factory()->create(['owner_id' => $owner->id]);

    // Test unauthorized user
    $this->actingAs($otherUser);
    $response = $this->deleteJson("/terrains/{$terrain->id}");
    $response->assertForbidden();

    // Test authorized owner
    $this->actingAs($owner);
    $response = $this->deleteJson("/terrains/{$terrain->id}");
    $response->assertOk();
    $this->assertSoftDeleted('terrains', ['id' => $terrain->id]);
});
