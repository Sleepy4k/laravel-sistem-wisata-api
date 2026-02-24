<?php

namespace App\Policies;

use App\Models\User;

class UserManagementPolicy
{
    /**
     * Only admin users may access user management endpoints.
     */
    private function isAdmin(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function view(User $user, User $target): bool
    {
        return $this->isAdmin($user);
    }

    public function store(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, User $target): bool
    {
        return $this->isAdmin($user);
    }

    public function delete(User $user, User $target): bool
    {
        // Prevent self-deletion
        return $this->isAdmin($user) && $user->id !== $target->id;
    }
}
