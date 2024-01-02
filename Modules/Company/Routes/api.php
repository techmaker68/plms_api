<?php

use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:api')->get('/company', function (Request $request) {
//     return $request->user();
// });

    Route::middleware([\App\Http\Middleware\SanitizeInput::class , 'auth:api'])
    ->group(function () {
        Route::apiResource('company', PLMSCompanyController::class);
    });