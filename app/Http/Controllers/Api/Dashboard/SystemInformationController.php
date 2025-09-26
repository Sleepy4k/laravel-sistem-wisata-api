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
        $debugMode = config('app.debug');
        $databaseDefault = config('database.default');

        return ApiResponse::success([
            'application' => [
                'name' => config('app.name'),
                'version' => config('app.version', '1.0.0'),
                'environment' => config('app.env'),
                'debug_mode' => $debugMode,
            ],
            'system' => [
                'php_version' => phpversion(),
                'backend_version' => app()->version(),
                'server_os' => PHP_OS_FAMILY,
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'timezone' => config('app.timezone'),
            ],
            'server' => [
                'memory_limit' => $debugMode ? ini_get('memory_limit') : 'Redacted',
                'max_execution_time' => $debugMode ? ini_get('max_execution_time') : 'Redacted',
                'upload_max_filesize' => $debugMode ? ini_get('upload_max_filesize') : 'Redacted',
                'post_max_size' => $debugMode ? ini_get('post_max_size') : 'Redacted',
            ],
            'database' => [
                'driver' => $databaseDefault,
                'connection_name' => $debugMode ? config('database.connections.'.$databaseDefault.'.database', 'N/A') : 'Redacted',
            ]
        ], 'System information retrieved successfully.', 200);
    }
}
