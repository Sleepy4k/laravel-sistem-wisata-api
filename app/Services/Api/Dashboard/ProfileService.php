<?php

namespace App\Services\Api\Dashboard;

use App\Foundations\Service;
use App\Http\Resources\Profile\UserBasicResource;
use App\Http\Resources\Profile\UserResource;

class ProfileService extends Service
{
    /**
     * Display a listing of the resource.
     */
    public function index(): mixed
    {
        $user = auth('api')->user();
        $user->load([
            'roles:id,name',
            'roles.permissions:id,name',
            'permissions:id,name',
            'logs' => fn($query) => $query->select('log_name', 'description', 'subject_type', 'event', 'subject_id', 'causer_type', 'causer_id', 'created_at')
            ->latest()
            ->limit(5)
        ]);

        $user->setRelation('transactions',
            $user->transactions()
            ->select('id', 'business_id', 'user_id', 'transaction_date', 'type')
            ->with('business:id,name,slug')
            ->paginate(10)
        );

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(): mixed
    {
        $user = auth('api')->user();
        $user = new UserBasicResource($user);

        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(array $request): bool
    {
        $user = auth('api')->user();

        if (isset($request['name'])) {
            $user->name = $request['name'];
        }

        if (isset($request['email'])) {
            $user->email = $request['email'];
        }

        if (isset($request['password'])) {
            if (!isset($request['old_password']) || !password_verify($request['old_password'], $user->password)) {
                return false;
            }

            $user->password = $request['password'];
        }

        return $user->save();
    }
}
