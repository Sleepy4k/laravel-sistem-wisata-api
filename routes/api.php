<?php

use App\Http\Controllers\Api\Admin;
use App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Api\Dashboard;
use App\Http\Controllers\Api\Error;
use App\Http\Controllers\Api\IoT;
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
Route::post('/check-session', Auth\SessionCheckController::class)->name('check.session');

Route::prefix('/iot')->name('iot.')->group(function () {
    Route::resource('/device', IoT\DeviceController::class)
        ->only(['index', 'store']);
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
        Route::get('/statistics', Dashboard\SummaryController::class)->name('statistics');
        Route::get('/system-information', Dashboard\SystemInformationController::class)->name('system.information');

        Route::prefix('admin')->name('admin.')->group(function () {
            Route::apiResource('/users', Admin\UserManagementController::class)
                ->parameters(['users' => 'user:id']);
        });

        Route::prefix('/profile')->name('profile.')->controller(Dashboard\ProfileController::class)->group(function () {
            Route::get('/detail', 'index')->name('index');
            Route::get('/', 'show')->name('show');
            Route::post('/', 'update')->name('update');
        });

        Route::prefix('{role}')->name('role.')->group(function () {
            Route::resource('/business', Dashboard\BusinessController::class)
                ->only(['store', 'show', 'update', 'destroy'])
                ->parameter('business', 'business:slug');

            Route::prefix('/{business:slug}')->name('section.')->group(function () {
                Route::name('misc.')->controller(Dashboard\SectionMiscController::class)->group(function () {
                    Route::get('/columns', 'columns')->name('columns');
                    Route::get('/fields', 'fields')->name('fields');
                    Route::get('/cards', 'cards')->name('cards');
                });

                Route::prefix('/export')->name('export.')->controller(Dashboard\ExportController::class)->group(function () {
                    Route::get('/excel', 'excel')->name('excel');
                    Route::get('/pdf', 'pdf')->name('pdf');
                    Route::get('/print', 'print')->name('print');
                });

                Route::prefix('/formula')->name('formula.')->controller(Dashboard\BusinessFormulaController::class)->group(function () {
                    Route::get('/', 'index')->name('index');
                    Route::post('/', 'update')->name('update');
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
