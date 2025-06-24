<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class)->in('Feature');
uses(Tests\TestCase::class)->in('Unit');

// Helper functions for tests
function createUser(array $attributes = [])
{
    return \App\Models\User::factory()->create($attributes);
}

function createTerrain(array $attributes = [])
{
    return \App\Models\Terrain::factory()->create($attributes);
}

function createBooking(array $attributes = [])
{
    return \App\Models\Booking::factory()->create($attributes);
}
