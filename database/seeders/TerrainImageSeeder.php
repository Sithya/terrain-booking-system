<?php

namespace Database\Seeders;

use App\Models\Terrain;
use App\Models\TerrainImage;
use Illuminate\Database\Seeder;

class TerrainImageSeeder extends Seeder
{
    public function run(): void
    {
        $terrains = Terrain::all();

        foreach ($terrains as $terrain) {
            // Create 2-5 images per terrain
            TerrainImage::factory(rand(2, 5))->create([
                'terrain_id' => $terrain->id,
            ]);
        }
    }
}
