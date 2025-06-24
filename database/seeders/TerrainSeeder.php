<?php

namespace Database\Seeders;

use App\Models\Terrain;
use App\Models\User;
use Illuminate\Database\Seeder;

class TerrainSeeder extends Seeder
{
    public function run(): void
    {
        // Create some users first
        $users = User::factory(10)->create();

        // Create terrains with existing users as owners
        Terrain::factory(50)->create([
            'owner_id' => fn() => $users->random()->id,
        ]);
    }
}
