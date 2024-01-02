<?php

use Illuminate\Http\Request;
use Modules\Loi\Http\Controllers\PLMSLoiController as NewPLMSLoiController;
use Modules\Loi\Http\Controllers\PLMSLoiApplicantController as NewPLMSLoiApplicantController;

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
        Route::apiResource('lois', PLMSLoiController::class);
        Route::apiResource('loi-applicants', PLMSLoiApplicantController::class);

        Route::controller(PLMSLoiApplicantController::class)->prefix('/loi-applicants')->group(function () {
            Route::post('store/bulk', 'saveLoiApplicantsBulk');
            Route::post('update/bulk', 'updateMultipleLoiApplicants');
            Route::post('remove/applicant', 'removeLoiApplicant');
            Route::post('passport/base64', 'loiPassportBase64');
            Route::delete('delete/payment-letter-copy/{id}', 'deletePaymentLetterCopy');
            Route::post('approved_email', 'sendLoiToApplicants');
            Route::post('generate-doc', 'generateDoc');
            Route::post('generate-pdf', 'generatePDF');
        });
        Route::controller(PLMSLoiController::class)->prefix('/lois')->group(function () {
            Route::delete('files/delete/{id}', 'deleteLoiFiles');
            Route::post('email_files', 'generateZipFile');
            Route::post('renew/{batch_no}', 'renewLoi');
        });
    });
