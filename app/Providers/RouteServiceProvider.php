<?php

namespace App\Providers;

use App\Facades\ApiResponse;
use App\Models\Role;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (app()->runningInConsole()) {
            return;
        }

        $roles = Role::query()->pluck('name')->toArray();
        $highestRole = config('rbac.role.highest');
        Route::pattern('role', implode('|', array_filter($roles, fn ($role) => $role !== $highestRole)));

        Route::pattern('business', '[a-z0-9-]+');

        RateLimiter::for('api', function ($request) {
            $identifier = auth('api')->check() ? auth('api')->id() : $request->ip();
            return Limit::perMinute(60)->by($identifier)
                ->response(function (Request $request, array $headers) {
                    return ApiResponse::error('Too Many Attempts.', 429, [
                        'limit' => $headers['X-RateLimit-Limit'],
                        'remaining' => $headers['X-RateLimit-Remaining'],
                        'reset' => $headers['X-RateLimit-Reset'],
                    ]);
                });
        });
    }
}
