<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Payment $payment): bool
    {
        return $user->id === $payment->booking->renter_id ||
               $user->id === $payment->booking->terrain->owner_id;
    }

    public function create(User $user, Booking $booking = null): bool
    {
        if (!$booking) {
            return false;
        }

        // Only the renter can create payments for their bookings
        return $user->id === $booking->renter_id &&
               in_array($booking->status, ['pending', 'approved']);
    }

    public function update(User $user, Payment $payment): bool
    {
        // Renter can update their own payments
        if ($user->id === $payment->booking->renter_id) {
            return true;
        }

        // Terrain owner can update payment status (for refunds, etc.)
        return $user->id === $payment->booking->terrain->owner_id;
    }

    public function delete(User $user, Payment $payment): bool
    {
        return $user->id === $payment->booking->renter_id ||
               $user->id === $payment->booking->terrain->owner_id;
    }

    public function restore(User $user, Payment $payment): bool
    {
        return $user->id === $payment->booking->terrain->owner_id;
    }

    public function forceDelete(User $user, Payment $payment): bool
    {
        return $user->id === $payment->booking->terrain->owner_id;
    }

    public function refund(User $user, Payment $payment): bool
    {
        return $user->id === $payment->booking->terrain->owner_id &&
               $payment->status === 'paid';
    }
}
