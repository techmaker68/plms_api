<?php
// ***********************************
// @author Syed, Aqsa, Saqib
// @create_date 18-07-2023
// ***********************************
namespace App\Http\Controllers;

use App\Models\Country;

use App\Utils\HttpStatusCode;

class GeneralController extends Controller
{

    public function getCountrys(){
        $code = HttpStatusCode::OK;
        $success_status =200;
        $message = 'success result';
        $datas = Country::orderBy('country_name_short_en', 'asc')->get();
        try {
            foreach ($datas as $data) {
                $return_result[] = array(
                    'ID' => $data->id,
                    'EngName' => $data->country_name_short_en,
                    'GbName' => $data->country_name_short_zh_cn,
                    'ArabicName' => json_decode('"' . $data->country_name_short_ar . '"'),
                    'nationality' => $data->nationality_en,
                    'nationality_in_arabic' => json_decode('"' . $data->nationality_ar . '"'),
                    'calling_code' => $data->calling_code,
                    'country_code' => $data->country_code_2,
                );
            }
        return response()->json([
            'status'=>$success_status,
            'message'=>$message,
            'code'=>$code,
            'result'=>$return_result
        ], $code,[], JSON_UNESCAPED_UNICODE);
        }catch (\Throwable $th) {
            $code = HttpStatusCode::INTERNAL_SERVER_ERROR;
            return $this->getResponse('', $code ,'', '');
        }
    }
}