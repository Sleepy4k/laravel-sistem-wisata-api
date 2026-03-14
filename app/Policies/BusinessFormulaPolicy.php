<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\User;

class BusinessFormulaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, string $role, Business $business): bool
    {
        return $user->can("{$role}.{$business->slug}.viewAny");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, string $role, Business $business): bool
    {
        return $user->can("{$role}.{$business->slug}.update");
    }
}
