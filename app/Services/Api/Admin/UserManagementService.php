<?php

namespace App\Services\Api\Admin;

use App\Facades\ApiResponse;
use App\Foundations\Service;
use App\Http\Resources\Admin\UserManagementResource;
use App\Models\User;

class UserManagementService extends Service
{
    /**
     * Return a paginated list of all users.
     */
    public function index(): mixed
    {
        $search = request()->input('search', null);
        $role   = request()->input('role', null);
        $size   = request()->input('per_page', 15);

        $query = User::with('roles:id,name')->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->role($role);
        }

        $users = $query->paginate($size);

        return ApiResponse::paginated($users->through(fn($u) => new UserManagementResource($u)), 'Users retrieved successfully.');
    }

    /**
     * Return details of a single user.
     */
    public function show(User $user): mixed
    {
        $user->loadMissing('roles:id,name');

        return ApiResponse::success(new UserManagementResource($user), 'User retrieved successfully.');
    }

    /**
     * Create a new user and assign a role.
     */
    public function store(array $data): mixed
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
        ]);

        $user->assignRole($data['role']);
        $user->loadMissing('roles:id,name');

        return ApiResponse::success(new UserManagementResource($user), 'User created successfully.', 201);
    }

    /**
     * Update an existing user.
     */
    public function update(User $user, array $data): mixed
    {
        $user->fill(array_filter([
            'name'     => $data['name'] ?? null,
            'email'    => $data['email'] ?? null,
            'password' => isset($data['password']) ? $data['password'] : null,
        ], fn($v) => !is_null($v)));

        $user->save();

        if (!empty($data['role'])) {
            $user->syncRoles([$data['role']]);
        }

        $user->loadMissing('roles:id,name');

        return ApiResponse::success(new UserManagementResource($user), 'User updated successfully.');
    }

    /**
     * Delete a user permanently.
     */
    public function destroy(User $user): mixed
    {
        $user->tokens()->delete();
        $user->delete();

        return ApiResponse::success(null, 'User deleted successfully.');
    }
}
