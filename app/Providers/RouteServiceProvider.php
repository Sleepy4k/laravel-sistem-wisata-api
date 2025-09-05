<?php

namespace App\Providers;

use App\Facades\ApiResponse;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
