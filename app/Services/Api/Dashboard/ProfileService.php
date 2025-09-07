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
        $user->load('roles:id,name', 'roles.permissions:id,name', 'permissions:id,name');
        $user->load('logs', 'transactions:business_id,user_id,transaction_date,type', 'transactions.business:id,name');
        $user = new UserResource($user);

        return $user;
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
            $user->password = $request['password'];
        }

        return $user->save();
    }
}
