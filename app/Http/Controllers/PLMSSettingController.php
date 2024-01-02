<?php
// ***********************************
// @author Syed, Aqsa
// @create_date 21-07-2023
// ***********************************
namespace App\Http\Controllers;

use App\Jobs\SendMultipleApplicantsEmail;
use Illuminate\Http\Request;
use App\Mail\ApplicantsMail;
use Modules\BloodTest\Entities\BloodTest;
use App\Models\Country;
use App\Models\PLMSSetting;
use Illuminate\Support\Facades\Mail;
use App\Utils\HttpStatusCode;
use Illuminate\Support\Facades\Validator;
use Modules\BloodTest\Entities\PLMSBloodApplicant;
use Modules\Loi\Entities\PLMSLoiApplicant;
use Illuminate\Support\Facades\Artisan;
use ZipArchive;

class PLMSSettingController extends Controller
{
    //
    public function test_emails(Request $request){
        try{
            $validator = validator::make($request->all(), [
                'emails'=>'required',
                ]);
                if ($validator->fails()) {
                $message = $validator->errors();
                $code = HttpStatusCode::UNPROCESSABLE_ENTITY;
                return $this->getResponse('' , $code ,'', $message);
            }
            $batch_no = $request->batch_no;
            $batch = BloodTest::where('batch_no',$batch_no)->with('blood_applicant.pax')->first();
            $batch_applicants = $batch ? $batch->blood_applicant()->where('scheduled_status',1)->get() : null;
            if( $batch_applicants  != null){
                $emails = explode(',', $request->emails);  // Split the emails using comma
                foreach ($emails as $email) {
                    Mail::bcc($email)->send(new ApplicantsMail($batch , $batch_applicants));
                }
            }
            $code = HttpStatusCode::OK;
            $message='Test Email Sent Successfully!';
            return $this->getResponse('', $code, '', $message);
        }catch (\Throwable $th) {
            $code = HttpStatusCode::INTERNAL_SERVER_ERROR;
            return $this->getResponse('', $code ,'', '');
        }
    }

    public function save_settings(Request $request)
    {
    try{
        if($request->blood_test_emails){
            PLMSSetting::where('parameter', 'blood_test_emails')
            ->update(['value' => $request->blood_test_emails ?? '00:00']);
        }
        if($request->blood_test_email_status){
            $value = $request->blood_test_email_status ? 1 : 0;
            PLMSSetting::where('parameter', 'blood_test_email_status')
            ->update(['value' => $value]);
        }
        
        if($request->blood_test_email_admins){
            PLMSSetting::where('parameter', 'blood_test_email_admins')
            ->update(['value' => $request->blood_test_email_admins ?? '']);
        }

        if($request->loi_admin_emails){
            PLMSSetting::where('parameter', 'loi_admin_emails')
            ->update(['value' => $request->loi_admin_emails ?? '']);
        }

        if($request->department_managers){
            PLMSSetting::where('parameter', 'department_managers')
            ->update(['value' => $request->department_managers ?? '']);
        }
        if($request->loi_focal_points){
            PLMSSetting::where('parameter', 'loi_focal_points')
            ->update(['value' => $request->loi_focal_points ?? '']);
        }

        $all_settings =  PLMSSetting::all();
        $return_result = (object) [
            'blood_test_emails' => $all_settings[0]->value,
            'blood_test_email_admins' => $all_settings[1]->value,
            'blood_test_email_status' => $all_settings[2]->value,
            'loi_admin_emails' => $all_settings[3]->value,
            'presentations' => $all_settings[4]->value,
            'department_managers' => $all_settings[5]->value,
            'loi_focal_points' => $all_settings[6]->value,
        ];
        $code = HttpStatusCode::OK;
        $message='Settings updated Successfully!';
        return $this->getResponse($return_result, $code, '', $message);
    }catch (\Throwable $th) {
            $code = HttpStatusCode::INTERNAL_SERVER_ERROR;
            return $this->getResponse('', $code ,'', '');
    }
    }

    public function get_settings()
    {
    try{
        $all_settings =  PLMSSetting::all();
        $return_result = (object) [
            'blood_test_emails' => $all_settings[0]->value,
            'blood_test_email_admins' => $all_settings[1]->value,
            'blood_test_email_status' => $all_settings[2]->value,
            'loi_admin_emails' => $all_settings[3]->value,
            'presentations' => $all_settings[4]->value,
            'department_managers' => $all_settings[5]->value,
            'loi_focal_points' => $all_settings[6]->value,
        ];
        $code = HttpStatusCode::OK;
        $message='Records Found!';
        return $this->getResponse($return_result, $code, '', $message);
    }catch (\Throwable $th) {
            $code = HttpStatusCode::INTERNAL_SERVER_ERROR;
            return $this->getResponse('', $code ,'', '');
    }
    }

    public function send_email_to_applicants($batch_no){
    try{
        $batch = BloodTest::where('batch_no',$batch_no)->with('blood_applicant.pax')->first();
        $batch_applicants = $batch->blood_applicant()->where('scheduled_status',1)->orderBy('sequence_no')->get();
        $all_settings =  PLMSSetting::all();
        $admin_emails = $all_settings[1]->value;
        if($batch_applicants != null){
            $noEmailApplicants = [];
            foreach($batch_applicants as $applicant){
                $pax = $applicant->pax ?? null;
                $email = $pax != null ? $pax->email :($applicant->email != null ? $applicant->email : null); 
                $applicant_seq = $applicant->sequence_no;   
                if($email != null){
                    SendMultipleApplicantsEmail::dispatch($email, $batch , $batch_applicants , $admin_emails);
                }
                else {
                    $noEmailApplicants[] = $applicant_seq;
                }
            }
            if (!empty($noEmailApplicants)) {
                $message = 'Email Sent, But the applicant ' . implode(', ', $noEmailApplicants) .' '. 'do not have email address.';
            }else{
                $message='Email Sent Successfully';
            }

        }else{
            $message='Applicants Not Found';
        }
        $code = HttpStatusCode::OK;
        return $this->getResponse('', $code, '', $message);
    }catch(\Throwable $th) {
        $code = HttpStatusCode::INTERNAL_SERVER_ERROR;
        return $this->getResponse('', $code ,'', '');
    } 
    }

    public function clear_all_caches(){
        
        Artisan::call('route:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('optimize:clear');
        return response()->json(['Cache cleared!']);
    }

    public function get_venues()
    {
        $result = [
            [
                'id' => 1,
                'label' => 'J Block'
            ],
            [
                'id' => 2,
                'label' => 'DB1 Clinic'
            ],
            [
                'id' => 3,
                'label' => 'PC Clinic'
            ],
        ];
        $code = HttpStatusCode::OK;
        $message='All Venues';
        return $this->getResponse($result, $code, '', $message);

    }

    public function sort_applicants(Request $request)
    {
        $loi_applicants = $request->input('applicants');
        $blood_applicants = $request->input('blood_applicants');
        try{
            if($loi_applicants){
                foreach ($loi_applicants as $index => $item) {
                    $model = PLMSLoiApplicant::find($item);
                    $model->sequence_no = $index + 1;  
                    $model->save();
                }
            }
            if($blood_applicants){
                foreach ($blood_applicants as $index => $item) {
                    $blood_applicant = PLMSBloodApplicant::find($item);
                    $blood_applicant->sequence_no = $index + 1; 
                    $blood_applicant->save();
                    $batch = $blood_applicant->blood_test;
                }
                $batch->processBatch($batch->batch_no);
            }
            $code = HttpStatusCode::OK;
            $message = 'data sorted successfully';
            return $this->getResponse('', $code ,'', $message);
        }catch (\Throwable $th) {
        $code = HttpStatusCode::INTERNAL_SERVER_ERROR;
        return $this->getResponse('', $code ,'', '');
        }
    }
        public function save_presentation(Request $request)
        {
            $validator = validator::make($request->all(), [
                'presentations'=>'required|array',
                ]);
                if ($validator->fails()) {
                $message = $validator->errors();
                $code = HttpStatusCode::UNPROCESSABLE_ENTITY;
                return $this->getResponse('' , $code ,'', $message);
            }
            $presentation_path = PLMSSetting::where('parameter', 'presentations')->first();
            if ($request->hasFile('presentations')) {
                $files = $request->file('presentations');
                $filePaths = [];
                $storedfilePaths = [];
                if ($presentation_path) {        
                    foreach ($files as $file) {
                        $path = $this->storeImage($file);
                        $filePaths[] = $path;
                        $storedfilePaths[]=url('/').'/media/'.$file;
                    }
                    $existing_files= $this->getExistingFiles();
                    $newFiles =array_merge($existing_files,$filePaths);
                    $commaSeparatedPaths = implode(',', $newFiles);
                    $presentation_path->value = $commaSeparatedPaths;
                    $presentation_path->save();

                    $currentUrls =$this->getCurrentPaths();
                    return response()->json(['message'=>'Files updated successfuly','files'=>$currentUrls],200);
        
                } else {
                    foreach ($files as $file) {
                        $path = $this->storeImage($file);
                        $filePaths[] = $path;
                        $storedfilePaths[]=url('/').'/media/'.$file; 
                    }
                    $commaSeparatedPaths = implode(',', $filePaths);
                    PLMSSetting::create([
                        'parameter' => 'presentations',
                        'value' => $commaSeparatedPaths ?? null,
                    ]);

                    $currentUrls =$this->getCurrentPaths();    
                    return response()->json(['message'=>'Files saved successfuly','files'=>$currentUrls],200);
                }
            }
        }
        
        public function get_presentations(){
            $files = $this->getCurrentPaths();
            return response()->json(['files'=>$files],200);
        }
        public function storeImage($image)
        {
            $name= $image->getClientOriginalName();
            $imageName = $name;
            $destination = 'media/loiApplicants/images/';
            $path= $image->move($destination,$imageName);
            $finalPath = substr($path, strlen('media/'));
            return  $finalPath;
        }


    public function delete_presentation($id){
        $presentation_path = PLMSSetting::where('parameter', 'presentations')->first();
        if($presentation_path){
            $paths=$presentation_path->value;
            $files=explode(',',$paths);
            if (isset($files[$id])) {
                $fileToDelete = public_path($files[$id]);
                unset($files[$id]);
                $files = array_values($files);
                if (file_exists($fileToDelete)) {
                    unlink($fileToDelete); // Delete the file
                }
                $newFiles= implode(',',$files);
                $presentation_path->value = $newFiles;
                $presentation_path->save();

               $curentFiles= $this->getCurrentPaths();
                return response()->json(['message'=>'file deleted successfully','files'=>$curentFiles],200);
            }

        }
    } 
    public function downloadPresentationZIp(Request $request){
        $presentation_path = PLMSSetting::where('parameter', 'presentations')->first();
        if($presentation_path->value!=''){
            $current_files= $presentation_path->value;
            $files = explode(',',$current_files);
            foreach ($files as $file) {
                $storedfilePathsZip[]=public_path('media/'.$file);
            }
            $zipFilePath = storage_path('app/temp_presentations/presentations');
            if (!is_dir($zipFilePath)) {
                mkdir($zipFilePath, 0777, true);
            }
            $zipFileName =  'Presentations.zip';
             $zipFilePath = $zipFilePath . '/' . $zipFileName;
             $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE) === true) {
                foreach ($storedfilePathsZip as $storedFilePath) {
                   if(file_exists($storedFilePath)){
                       $fileName = basename($storedFilePath);
                       $zip->addFile($storedFilePath, $fileName);
                    }
                }
                $zip->close();
                return response()
                    ->download($zipFilePath, 'presentations.zip',['Content-Type' => 'application/zip'])
                    ->deleteFileAfterSend(true);
            } else {
                return response()->json(['error' => 'Failed to create the zip archive'], 500);
            }
        }else {
            return response()->json(['error'=>'No presentation availabe.'],400);
        }
    }

    public function getCurrentPaths(){
        $presentation_path = PLMSSetting::where('parameter', 'presentations')->first();
        if ($presentation_path && !empty($presentation_path->value)) {
            $storedfilePaths = [];
            $current_files = $presentation_path->value;
            $files = explode(',', $current_files);
            
            foreach ($files as $file) {
                $storedfilePaths[] = url('/') . '/media/' . $file;
            }
            
            return $storedfilePaths;

        } else {
            return []; // Return an empty array if there is no data
        }
    }
    public function getExistingFiles(){
        $presentation_path = PLMSSetting::where('parameter', 'presentations')->first();
        if ($presentation_path && !empty($presentation_path->value)) {
            $storedfilePaths = [];
            $current_files = $presentation_path->value;
            $files = explode(',', $current_files);
            
            foreach ($files as $file) {
                $storedfilePaths[] =  $file;
            }
            
            return $storedfilePaths;
        } else {
            return []; // Return an empty array if there is no data
        }
    }

    public function get_countries(){
        $code = HttpStatusCode::OK;
        $message = 'Countries Found';
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
                );
            }
            return $this->getResponse($return_result, $code, $data->count(), $message);
        }catch (\Throwable $th) {
            $code = HttpStatusCode::INTERNAL_SERVER_ERROR;
            return $this->getResponse('', $code ,'', '');
        }
    }
}
