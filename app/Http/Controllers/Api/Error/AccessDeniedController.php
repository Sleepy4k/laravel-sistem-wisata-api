<?php

namespace App\Http\Controllers\Api\Error;

use App\Facades\ApiResponse;
use App\Foundations\Controller;
use Illuminate\Http\Request;

class AccessDeniedController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return ApiResponse::error('You cannot access this resource', 403, [
            'error_code' => 'access_denied',
            'caused_by' => 'Authenticated user trying to access a guest-only resource or a resource they do not have permission to access',
        ]);
    }
}
