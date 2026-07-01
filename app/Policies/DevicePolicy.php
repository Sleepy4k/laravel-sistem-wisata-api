<?php

namespace App\Policies;

use App\Models\User;

class DevicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can store models.
     */
    public function store(?User $user, string $role): bool
    {
        return true;
    }
}
