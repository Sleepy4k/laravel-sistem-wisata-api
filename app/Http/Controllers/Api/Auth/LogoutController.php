<?php

namespace App\Http\Controllers\Api\Auth;

use App\Foundations\Controller;
use App\Services\Api\Auth\LogoutService;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LogoutService $service, Request $request)
    {
        return $service->invoke($request);
    }
}
