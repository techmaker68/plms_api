<?php

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

Route::middleware([\App\Http\Middleware\SanitizeInput::class, 'auth:api'])
    ->group(function () {
        Route::apiResource('bloodtests', PLMSBloodTestController::class);
        Route::apiResource('blood-applicants', PLMSBloodApplicantController::class);

        Route::controller(PLMSBloodApplicantController::class)->prefix('/blood-applicants')->group(function () {
            Route::post('/reschedule', 'rescheduleApplicant');
            Route::post('/bulk_update',  'updateApplicantsInBulk');
            Route::post('/renew_appointment/{id}',  'renewAppointment');
            Route::post('/add_penalty/{id}',  'addPenalty');
            Route::post('/report',  'sendApplicantsNote');
            Route::delete('/remove/multiple',  'removeBloodApplicant');
            Route::get('/pax_detail/{id}',  'getPaxDetail');
            Route::post('/generate-doc',  'generateDoc');
            Route::post('/generate-pdf',  'generatePDF');
        });
    });
