<?php

namespace App\Http\Controllers\Api;

use App\Facades\ApiResponse;
use App\Foundations\Controller;
use Illuminate\Http\Request;

class RootController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return ApiResponse::success([
            'status' => 'server is running properly',
        ], 'Welcome to the API', 200);
    }
}
