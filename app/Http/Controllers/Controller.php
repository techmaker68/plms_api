<?php
// ***********************************
// @author Syed, Aqsa, Saqib
// @create_date 18-07-2023
// ***********************************
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Utils\HttpStatusCode;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @OA\OpenApi(
     *     @OA\Info(
     *         version="1.0",
     *         title="Plms Api",
     *         description="Plms Module's Api",
     *     )
     * )
     */
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getResponse($result = array() , $code , $total = NULL , $message = NULL)
    { 
        if ($code == HttpStatusCode::OK) {
            return response()->json([
                'data' => $result, 
                'total' => $total , 
                'status'=>'success' ,
                'message' => $message,
                'code' => HttpStatusCode::OK], HttpStatusCode::OK);
        }else if($code == HttpStatusCode::UNPROCESSABLE_ENTITY){
            return response()->json([
                'data' => $result, 
                'total' => $total , 
                'status'=>'failed' , 
                'message' => $message,
                'code' => HttpStatusCode::UNPROCESSABLE_ENTITY], HttpStatusCode::UNPROCESSABLE_ENTITY);
        }
        else if($code == HttpStatusCode::NOT_FOUND){
            return response()->json([
                'data' => $result, 
                'total' => $total , 
                'status'=>'failed' , 
                'message' => $message,
                'code' => HttpStatusCode::NOT_FOUND], HttpStatusCode::NOT_FOUND);
        }
        else if($code == HttpStatusCode::INTERNAL_SERVER_ERROR){
            return response()->json([
                'data' => $result, 
                'total' => $total , 
                'status'=>'failed' , 
                'message' => $message ?? 'Something Went Wrong Try again later.',
                'code' => HttpStatusCode::INTERNAL_SERVER_ERROR], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
        else if($code == HttpStatusCode::UNAUTHORIZED){
            return response()->json([
                'data' => $result, 
                'total' => $total , 
                'status'=>'unauthorized' , 
                'message' => 'The passowrd or email is wrong',
                'code' => HttpStatusCode::INTERNAL_SERVER_ERROR], HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
        else{
            return response()->json([
                'data' => $result, 
                'total' => $total , 
                'status'=>'failed' , 
                'message' => 'Invalid Request',
                'code' => HttpStatusCode::BAD_REQUEST], HttpStatusCode::BAD_REQUEST);
        }
    }
}
