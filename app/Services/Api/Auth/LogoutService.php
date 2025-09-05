<?php

namespace App\Services\Api\Auth;

use App\Facades\ApiResponse;
use App\Foundations\Service;
use Illuminate\Http\Request;

class LogoutService extends Service
{
    /**
     * Handle the incoming request.
     */
    public function invoke(Request $request): mixed
    {
        $request->user()->tokens()->delete();

        return ApiResponse::success(null, 'Logout successful', 200);
    }
}
