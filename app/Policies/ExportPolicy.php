<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\User;

class ExportPolicy
{
    /**
     * Determine whether the user can export as Excel.
     */
    public function excel(User $user, string $role, Business $business): bool
    {
        return $user->can("{$role}.{$business->slug}.viewAny");
    }

    /**
     * Determine whether the user can export as PDF.
     */
    public function pdf(User $user, string $role, Business $business): bool
    {
        return $user->can("{$role}.{$business->slug}.viewAny");
    }

    /**
     * Determine whether the user can export for print.
     */
    public function print(User $user, string $role, Business $business): bool
    {
        return $user->can("{$role}.{$business->slug}.viewAny");
    }
}
