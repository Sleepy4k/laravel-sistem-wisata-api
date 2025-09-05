<?php

use App\Facades\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
|
| You can list public endpoint for any user in here. These routes are not guarded
| by any authentication system. In other words, any user can access it directly.
| Remember not to list anything of importance, use authenticate route instead.
*/

Route::get('/ping', function () {
    return ApiResponse::success('Server service is working', 'Pong');
});

/*
|--------------------------------------------------------------------------
| Unauthenticated Route
|--------------------------------------------------------------------------
|
| You can list public endpoint for any user in here. These routes are meant
| to be used for guests and are not guarded by any authentication system.
| Remember not to list anything of importance, use authenticate route instead.
*/

Route::middleware('guest:sanctum')->group(function () {
    Route::get('/login', function () {
        return ApiResponse::success(null, 'Login endpoint is working');
    })->name('login');

    Route::get('/register',function () {
        return ApiResponse::success(null, 'Register endpoint is working');
    })->name('register');
});

/*
|--------------------------------------------------------------------------
| Authenticated Route
|--------------------------------------------------------------------------
|
| In here you can list any route for authenticated user. These routes
| are meant to be used privately since the access is exclusive to authenticated
| user who had obtained their access through the login process.
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return ApiResponse::success(null, 'Successfully logged out');
    })->name('logout');

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('user');
});
