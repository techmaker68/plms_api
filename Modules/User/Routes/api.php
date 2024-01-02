<?php

use Illuminate\Http\Request;
use Modules\User\Http\Controllers\PLMSUserLoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware([\App\Http\Middleware\SanitizeInput::class])
    ->group(function () {
        Route::middleware(['auth:api'])->prefix('auth')->group(function () {
            Route::apiResource('users', PLMSUserController::class);
            Route::post('/logout', [PLMSUserLoginController::class, 'logout']);
            Route::get('/current_user', [PLMSUserLoginController::class, 'getCurrentUser']);
        });
    });
Route::post('/auth/login', [PLMSUserLoginController::class, 'login']);
