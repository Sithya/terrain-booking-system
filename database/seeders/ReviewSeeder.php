<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $completedBookings = Booking::where('status', 'completed')->get();

        foreach ($completedBookings as $booking) {
            // 70% chance of having a review for completed bookings
            if (rand(1, 100) <= 70) {
                Review::factory()->create([
                    'terrain_id' => $booking->terrain_id,
                    'user_id' => $booking->renter_id,
                ]);
            }
        }
    }
}

