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
     * Determine whether the user can view the model.
     */
    public function view(?User $user, string $role): bool
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

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, string $role): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, string $role): bool
    {
        return true;
    }
}
