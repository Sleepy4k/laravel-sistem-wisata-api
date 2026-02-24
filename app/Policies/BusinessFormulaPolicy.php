<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\BusinessFormula;
use App\Models\User;

class BusinessFormulaPolicy
{
    public function viewAny(User $user, string $role, Business $business): bool
    {
        return $user->can("{$role}.{$business->slug}.viewAny");
    }

    public function store(User $user, string $role, Business $business): bool
    {
        return $user->can("{$role}.{$business->slug}.store");
    }

    public function update(User $user, string $role, Business $business, BusinessFormula $formula): bool
    {
        return $user->can("{$role}.{$business->slug}.update") && $formula->business_id === $business->id;
    }

    public function delete(User $user, string $role, Business $business, BusinessFormula $formula): bool
    {
        return $user->can("{$role}.{$business->slug}.delete") && $formula->business_id === $business->id;
    }
}
