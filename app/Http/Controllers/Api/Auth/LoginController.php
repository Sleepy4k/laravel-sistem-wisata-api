<?php

namespace App\Http\Controllers\Api\Auth;

use App\Foundations\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Api\Auth\LoginService;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginService $service, LoginRequest $request): mixed
    {
        return $service->invoke($request->validated());
    }
}
