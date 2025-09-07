<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Facades\ApiResponse;
use App\Foundations\Controller;
use Illuminate\Http\Request;

class SystemInformationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return ApiResponse::success([
            'app_name'    => config('app.name'),
            'app_version' => config('app.version'),
            'php_version' => phpversion(),
            'laravel_version' => app()->version(),
            'server_os'   => php_uname(),
        ], 'Successfully retrieved system information.', 200);
    }
}
