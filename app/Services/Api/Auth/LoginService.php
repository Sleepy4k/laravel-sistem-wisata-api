<?php

namespace App\Services\Api\Auth;

use App\Facades\ApiResponse;
use App\Foundations\Service;
use App\Http\Resources\Auth\LoginResource;
use App\Models\User;

class LoginService extends Service
{
    /**
     * Handle the incoming request.
     */
    public function invoke(array $request): mixed
    {
        $user = User::select('id', 'name', 'email', 'password', 'created_at')->where('email', $request['email'])->first();

        if (!$user) {
            return ApiResponse::error('Currently we do not recognize your email', 404, [
                'email' => ['Email not found'],
            ]);
        }

        if (!password_verify($request['password'], $user->password)) {
            return ApiResponse::error('The provided credentials are incorrect.', 401, [
                'password' => ['The provided password is incorrect'],
            ]);
        }

        $user->load('roles:id,name', 'roles.permissions:id,name', 'permissions:id,name');
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success([
            'token_type' => 'Bearer',
            'access_token' => $token,
            'user' => new LoginResource($user),
        ], 'Login successful', 200);
    }
}
