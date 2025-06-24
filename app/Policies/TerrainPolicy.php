<?php

namespace App\Policies;

use App\Models\Terrain;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TerrainPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Terrain $terrain): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Terrain $terrain): bool
    {
        return $user->id === $terrain->owner_id;
    }

    public function delete(User $user, Terrain $terrain): bool
    {
        return $user->id === $terrain->owner_id;
    }

    public function restore(User $user, Terrain $terrain): bool
    {
        return $user->id === $terrain->owner_id;
    }

    public function forceDelete(User $user, Terrain $terrain): bool
    {
        return $user->id === $terrain->owner_id;
    }

    public function manageImages(User $user, Terrain $terrain): bool
    {
        return $user->id === $terrain->owner_id;
    }

    public function manageBookings(User $user, Terrain $terrain): bool
    {
        return $user->id === $terrain->owner_id;
    }
}
