<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $bookings = Booking::where('status', '!=', 'rejected')->get();

        foreach ($bookings as $booking) {
            // Create payment for approved/completed bookings
            if (in_array($booking->status, ['approved', 'completed'])) {
                Payment::factory()->paid()->create([
                    'booking_id' => $booking->id,
                    'amount_paid' => $booking->total_price,
                ]);
            }
        }
    }
}
