<?php

namespace App\Http\Controllers\Api\Auth;

use App\Foundations\Controller;
use App\Services\Api\Auth\SessionCheckService;
use Illuminate\Http\Request;

class SessionCheckController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SessionCheckService $service, Request $request)
    {
        return $service->invoke($request);
    }
}
