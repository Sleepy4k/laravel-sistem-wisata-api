<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\User;

class ManageBusinessPolicy
{
    /**
     * Determine whether the user can store models.
     */
    public function store(User $user, string $role): bool
    {
        return $user->can("manage.{$role}.business");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, string $role, Business $business): bool
    {
        return $user->can("manage.{$role}.business");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, string $role, Business $business): bool
    {
        return $user->can("manage.{$role}.business");
    }
}
