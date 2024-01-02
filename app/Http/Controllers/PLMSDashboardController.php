<?php
// ***********************************
// @author Syed, Aqsa, Saqib
// @create_date 21-07-2023
// ***********************************
namespace App\Http\Controllers;

use Modules\Pax\Entities\PLMSPax;
use Modules\Passport\Entities\PLMSPassport;
use Modules\Visa\Entities\PLMSVisa;
use Modules\BloodTest\Entities\BloodTest;
use Modules\Loi\Entities\PLMSLoi;

use App\Utils\HttpStatusCode;


class PLMSDashboardController extends Controller
{
    //
    public function dashboard_stats(){
        try{
            $totalPaxes = PLMSPax::count();
            $onboardPaxesCount = PLMSPax::where('status', 1)->count();
            $offboardPaxesCount = PLMSPax::where('status', 2)->count();
            $expatPaxesCount = PLMSPax::where('type', 'Expat')->count();
            $paxesWithoutPassport = PLMSPax::doesntHave('passports')->count();
            $paxesWithoutVisas = PLMSPax::doesntHave('visas')->count();
            $plms_paxes_stats = (object)[
                'total' => $totalPaxes,
                'onboard' => $onboardPaxesCount,
                'offboard' => $offboardPaxesCount,
                'expat' =>  $expatPaxesCount,
                'paxes_without_passports' =>  $paxesWithoutPassport,
                'paxes_without_visas' =>  $paxesWithoutVisas,
            ];
            // passports
            $totalPassport  = PLMSPassport::count();
            $activePassportCount = PLMSPassport::where('status', '1')->count();
            $expiredPassportCount = PLMSPassport::where('status', '2')->count();
            $toBeRenewedPassportCount = PLMSPassport::where('status', '3')->count();
            $cancelledPassportCount = PLMSPassport::where('status', '4')->count();
            $plms_passports_stats = (object)[
                'total' => $totalPassport,
                'active' => $activePassportCount,
                'expired' => $expiredPassportCount,
                'to_be_renewed' =>  $toBeRenewedPassportCount,
                'cancelled' =>  $cancelledPassportCount
            ];
            // visas
            $totalVisa  = PLMSVisa::count();
            $activeVisaCount = PLMSVisa::where('status', '1')->count();
            $expiredVisaCount = PLMSVisa::where('status', '2')->count();
            $toBeRenewedVisaCount = PLMSVisa::where('status', '3')->count();
            $cancelledVisaCount = PLMSVisa::where('status', '4')->count();
            $plms_visas_stats = (object)[
                'total' => $totalVisa,
                'active' => $activeVisaCount,
                'expired' => $expiredVisaCount,
                'to_be_renewed' =>  $toBeRenewedVisaCount,
                'cancelled' =>  $cancelledVisaCount
            ];
            // blood tests
            $totalBloodTest  = BloodTest::count();
            $batch = BloodTest::orderBy('batch_no', 'desc')->first();
            if($batch){
                $lastTestBatchNo = $batch->batch_no;
                $submitted  = $batch->blood_applicant()->count();
                $tested  = $batch->blood_applicant()->where('attendance', 1)->count();
                $returned  = $batch->blood_applicant()->whereNotNull('passport_return_date')->count();
                $scheduled  = $batch->blood_applicant()->where('scheduled_status',1)->count();
                $plms_blood_test_stats = (object)[
                    'lastTestBatchNo' => $lastTestBatchNo,
                    'total_records' => $totalBloodTest,
                    'submitted' => $submitted,
                    'tested' => $tested,
                    'returned' =>  $returned,
                    'scheduled' =>  $scheduled
                ];
            }else{
                $plms_blood_test_stats = (object)[
                    'total_records' => $totalBloodTest,
                    'submitted' => 0,
                    'tested' => 0,
                    'returned' =>  0,
                    'scheduled' => 0
                ];
            }
            // loi
            $closedLoiRequests = PLMSLOI::whereNotNull('close_date')->count();
            $openLoiRequests = PLMSLOI::whereNull('close_date')->count();
            $latest_loi = PLMSLOI::orderBy('batch_no', 'desc')->first();
            if($latest_loi){
                $latestBatchNo = $latest_loi->batch_no;
                $latestBatchId = $latest_loi->id;
                $totalApplicants = $latest_loi->applicants()->count();
                $approved = $latest_loi->applicants()->where('status',0)->count();
                $rejected = $latest_loi->applicants()->where('status',1)->count();
                $cancelled = $latest_loi->applicants()->where('status',2)->count();
                $give_up = $latest_loi->applicants()->where('status',3)->count();
                $plms_lois_stats = (object)[
                    'latest_batch_id' => $latestBatchId,
                    'latest_batch' => $latestBatchNo,
                    'closed_loi' => $closedLoiRequests,
                    'open_loi' => $openLoiRequests,
                    'total_applicants' => $totalApplicants,
                    'approved' =>  $approved,
                    'rejected' => $rejected,
                    'cancelled' => $cancelled,
                    'give_up' => $give_up,
                ];
            }else{
                $plms_lois_stats = (object)[
                    'latest_batch' => 0,
                    'latest_batch_id' => 0,
                    'closed_loi' => $closedLoiRequests,
                    'open_loi' => $openLoiRequests,
                    'total_applicants' => 0,
                    'approved' =>  0,
                    'rejected' => 0,
                    'cancelled' => 0,
                    'give_up' => 0,
                ];
            }
            $result = [
                'paxes_stats' => $plms_paxes_stats,
                'passports_stats' => $plms_passports_stats,
                'visas_stats' => $plms_visas_stats,
                'blood_test_stats' => $plms_blood_test_stats,
                'lois_stats' => $plms_lois_stats,
            ];
            $code = HttpStatusCode::OK;
            $message='Records Found!';
            return $this->getResponse($result, $code, '', $message);
        }catch (\Throwable $th) {
                $code = HttpStatusCode::INTERNAL_SERVER_ERROR;
                return $this->getResponse('', $code ,'', '');
        }
    }
}
