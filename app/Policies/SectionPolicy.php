<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\User;

class SectionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, string $role, Business $business): bool
    {
        return $user->can("{$role}.{$business->slug}.viewAny");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, string $role, Business $business): bool
    {
        return $user->can("{$role}.{$business->slug}.view");
    }

    /**
     * Determine whether the user can create models.
     */
    public function store(User $user, string $role, Business $business): bool
    {
        return $user->can("{$role}.{$business->slug}.store");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, string $role, Business $business): bool
    {
        return $user->can("{$role}.{$business->slug}.update");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, string $role, Business $business): bool
    {
        return $user->can("{$role}.{$business->slug}.delete");
    }
}
