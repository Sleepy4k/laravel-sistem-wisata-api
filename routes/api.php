<?php

use App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Api\Error;
use App\Http\Controllers\Api\RootController;
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

Route::any('/', RootController::class)->name('root');
Route::get('/access-denied', Error\AccessDeniedController::class)->name('access.denied');

/*
|--------------------------------------------------------------------------
| Unauthenticated Route
|--------------------------------------------------------------------------
|
| You can list public endpoint for any user in here. These routes are meant
| to be used for guests and are not guarded by any authentication system.
| Remember not to list anything of importance, use authenticate route instead.
*/

Route::middleware('guest:api')->group(function () {
    Route::post('/login', Auth\LoginController::class)->name('login');
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

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', Auth\LogoutController::class)->name('logout');
});
