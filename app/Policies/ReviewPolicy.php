<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\Terrain;
use App\Models\User;

class ReviewPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Review $review): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true; // We'll check specific conditions in the request/controller
    }

    public function update(User $user, Review $review): bool
    {
        return $user->id === $review->user_id;
    }

    public function delete(User $user, Review $review): bool
    {
        // User can delete their own review, terrain owner can delete any review
        return $user->id === $review->user_id || 
               $user->id === $review->terrain->owner_id;
    }

    public function restore(User $user, Review $review): bool
    {
        return $user->id === $review->terrain->owner_id;
    }

    public function forceDelete(User $user, Review $review): bool
    {
        return $user->id === $review->terrain->owner_id;
    }
}
