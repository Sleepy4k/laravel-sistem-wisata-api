<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\User;

class SectionMiscPolicy
{
    /**
     * Determine whether the user can view columns.
     */
    public function viewColumns(User $user, string $role, Business $business): bool
    {
        return $user->can("{$role}.{$business->slug}.viewAny");
    }

    /**
     * Determine whether the user can view fields.
     */
    public function viewFields(User $user, string $role, Business $business): bool
    {
        $prefix = "{$role}.{$business->slug}";
        return $user->can("{$prefix}.store") || $user->can("{$prefix}.update");
    }

    /**
     * Determine whether the user can view cards.
     */
    public function viewCards(User $user, string $role, Business $business): bool
    {
        return $user->can("{$role}.{$business->slug}.viewAny");
    }
}
