<?php
// ***********************************
// @author Syed, Aqsa
// @create_date 21-07-2023
// ***********************************
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\SortingController;
use App\Http\Controllers\PLMSExcelController;
use App\Http\Controllers\PLMSSettingController;
use App\Http\Controllers\PLMSDashboardController;
use Modules\Visa\Entities\PLMSVisa;
use Modules\Passport\Entities\PLMSPassport;
use Modules\Pax\Entities\PLMSPax;
use Modules\Loi\Entities\PLMSLoiApplicant;



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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['GeneralController'], function () {
    //Countrys data
    Route::get('getCountrys', [GeneralController::class, 'getCountrys']); 
});

Route::group(['middleware' => 'auth:api'], function () {
    
    Route::post('sort_applicants_by_sequence', [SortingController::class, 'sortApplicants']);

    Route::group(['PLMSSettingController'], function () {
        Route::post('/test_emails',[PLMSSettingController::class,'test_emails']);
        Route::get('/get_settings',[PLMSSettingController::class,'get_settings']);
        Route::post('/save_settings',[PLMSSettingController::class,'save_settings']);
        Route::post('/send_email_to_applicants/{batch_no}',[PLMSSettingController::class,'send_email_to_applicants']);
        Route::get('/get_venues',[PLMSSettingController::class,'get_venues']);
        Route::post('/save_presentations', [PLMSSettingController::class, 'save_presentation']);
        Route::get('/get_presentations', [PLMSSettingController::class, 'get_presentations']);
        Route::delete('/delete_presentation/{id}', [PLMSSettingController::class, 'delete_presentation']);
        Route::get('/download_presentation', [PLMSSettingController::class, 'downloadPresentationZIp']);
        // Route::get('get_countries', [PLMSSettingController::class, 'get_countries']); 
    });

    Route::group(['PLMSDashboardController'], function () {
        Route::get('/dashboard_stats',[PLMSDashboardController::class,'dashboard_stats']);
    }); 

    Route::group(['PLMSExcelController'], function () {

        Route::post('/excel_import',[PLMSExcelController::class,'importView'])->name('import-view');
        Route::get('/download_excel',[PLMSExcelController::class,'downloadExcel']);
    }); 

    Route::group(['ExportController'], function () {

        Route::post('/export',[ExportController::class,'export'])->name('export');
    }); 
});

// clear cache
Route::get('/clear_all_caches',[PLMSSettingController::class,'clear_all_caches']);



Route::get('/process_visas_and_passports', function () {
    $visas = PLMSVisa::all();
    $passport = null;
    foreach ($visas as $visa) {
        if (!empty($visa->passport_no)) {
            $passport = PLMSPassport::where('passport_no', $visa->passport_no)->first();
        } else if(!empty($visa->pax_id)){
            $pax = PLMSPax::where('pax_id',$visa->pax_id)->first();
            $passport = $pax ? $pax->latestPassport : null;
        }
        $visa->passport_id = $passport != null ? $passport->id : null;
        $visa->save();
    }
    return response()->json($visas);
});

Route::get('/process_visas_and_lois', function () {
    $visas = PLMSVisa::all();
    $passport = null;
    foreach ($visas as $visa) {
        if (!empty($visa->pax_id)) {
            $applicant = PLMSLoiApplicant::where('pax_id', $visa->pax_id)->latest()->first();
            if($applicant){
                if($applicant->loi_application){
                    $loi_id = $applicant->loi_application->id;
                    $visa->loi_id = $loi_id ;
                    $visa->save();
                }else{
                    dd($applicant);
                }
            }
        } else {
           dd($visa->pax_id);
        }
    }
    return response()->json($visas);
});








