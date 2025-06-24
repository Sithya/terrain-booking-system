<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Terrain;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $terrains = Terrain::all();
        $users = User::all();

        foreach ($terrains as $terrain) {
            // Create 1-3 bookings per terrain
            Booking::factory(rand(1, 3))->create([
                'terrain_id' => $terrain->id,
                'renter_id' => $users->where('id', '!=', $terrain->owner_id)->random()->id,
            ]);
        }
    }
}

