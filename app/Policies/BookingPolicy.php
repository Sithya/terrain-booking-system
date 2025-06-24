<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\Terrain;
use App\Models\User;

class BookingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Booking $booking): bool
    {
        return $user->id === $booking->renter_id ||
               $user->id === $booking->terrain->owner_id;
    }

    public function create(User $user, Terrain $terrain = null): bool
    {
        // User cannot book their own terrain
        if ($terrain && $user->id === $terrain->owner_id) {
            return false;
        }

        return true;
    }

    public function update(User $user, Booking $booking): bool
    {
        // Renter can update if booking is pending
        if ($user->id === $booking->renter_id && $booking->status === 'pending') {
            return true;
        }

        // Owner can always update status
        return $user->id === $booking->terrain->owner_id;
    }

    public function delete(User $user, Booking $booking): bool
    {
        // Can cancel if it's pending or approved (not yet completed)
        return ($user->id === $booking->renter_id ||
                $user->id === $booking->terrain->owner_id) &&
               in_array($booking->status, ['pending', 'approved']);
    }

    public function restore(User $user, Booking $booking): bool
    {
        return $user->id === $booking->renter_id ||
               $user->id === $booking->terrain->owner_id;
    }

    public function forceDelete(User $user, Booking $booking): bool
    {
        return $user->id === $booking->terrain->owner_id;
    }

    public function approve(User $user, Booking $booking): bool
    {
        return $user->id === $booking->terrain->owner_id &&
               $booking->status === 'pending';
    }

    public function reject(User $user, Booking $booking): bool
    {
        return $user->id === $booking->terrain->owner_id &&
               $booking->status === 'pending';
    }

    public function complete(User $user, Booking $booking): bool
    {
        return $user->id === $booking->terrain->owner_id &&
               $booking->status === 'approved';
    }
}
