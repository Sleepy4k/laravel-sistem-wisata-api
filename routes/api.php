<?php

use App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Api\Dashboard;
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

    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/sidebar', Dashboard\SidebarController::class)->name('sidebar');
        Route::get('/system-information', Dashboard\SystemInformationController::class)->name('system.information');

        Route::controller(Dashboard\ProfileController::class)->group(function () {
            Route::get('/profile/detail', 'index')->name('profile.index');
            Route::get('/profile', 'show')->name('profile.show');
            Route::post('/profile', 'update')->name('profile.update');
        });

        Route::prefix('{role}')->name('role.')->group(function () {
            Route::resource('/business', Dashboard\BusinessController::class)
                ->only(['store', 'update', 'destroy'])
                ->parameter('business', 'business:slug');

            Route::prefix('/{business:slug}')->name('section.')->group(function () {
                Route::controller(Dashboard\SectionMiscController::class)->group(function () {
                    Route::get('/columns', 'columns')->name('misc.columns');
                    Route::get('/fields', 'fields')->name('misc.fields');
                    Route::get('/cards', 'cards')->name('misc.cards');
                });

                Route::controller(Dashboard\SectionController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'store')->name('store');
                    Route::get('/{transaction}', 'show')->name('show');
                    Route::put('/{transaction}', 'update')->name('update');
                    Route::delete('/{transaction}', 'destroy')->name('destroy');
                });
            });
        });
    });
});
