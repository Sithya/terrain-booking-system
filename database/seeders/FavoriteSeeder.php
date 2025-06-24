<?php

namespace Database\Seeders;

use App\Models\Favorite;
use App\Models\Terrain;
use App\Models\User;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $terrains = Terrain::all();

        foreach ($users as $user) {
            // Each user favorites 3-8 terrains
            $favoriteTerrains = $terrains->random(rand(3, 8));

            foreach ($favoriteTerrains as $terrain) {
                Favorite::firstOrCreate([
                    'user_id' => $user->id,
                    'terrain_id' => $terrain->id,
                ]);
            }
        }
    }
}
