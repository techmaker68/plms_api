<?php

namespace Modules\BloodTest\Transformers;

use App\Resources\CountryResource;
use Modules\Pax\Transformers\PaxResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Company\Transformers\CompanyResource;
use Modules\Department\Transformers\DepartmentResource;

class BloodApplicantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
            $array = [
                'id' => $this->id ?? '',
                'pax' => new PaxResource($this->whenLoaded('pax')),
                'blood_test' => new BloodTestResource($this->whenLoaded('blood_test')),
                'country_code' => new CountryResource($this->whenLoaded('countryCode')),
                'batch_no' => $this->batch_no ?? '',
                'arrival_date' => $this->arrival_date ?? '',
                'departure_date' => $this->departure_date ?? '',
                'passport_submit_date' => $this->passport_submit_date ?? '',
                'passport_return_date' => $this->passport_return_date ?? '',
                'hiv_expire_date' => $this->hiv_expire_date ?? '',
                'hbs_expire_date' => $this->hbs_expire_date ?? '',
                'appoint_time' => $this->appoint_time ?? '',
                'appoint_date' => $this->blood_test->test_date ?? '',
                'attendance' => $this->attendance ?? '',
                'task_purposes' => $this->task_purposes ?? '',
                'sequence_no' => $this->sequence_no ?? '',
                'blood_test_types' => $this->blood_test_types ?? '',
                'remarks' => $this->remarks ?? '',
                'passport_issue_date' => $this->passport_issue_date ?? '',
                'passport_expiry_date' => $this->passport_expiry_date ?? '',
                'scheduled_status' => $this->scheduled_status ?? '',
                'penalty_fee' =>  $this->penalty_fee ?? '',
                'visa_penalty_fee' =>  $this->visa_penalty_fee ?? '',
                'visa_penalty_remarks' =>  $this->visa_penalty_remarks ?? '',
                'penalty_remarks' =>  $this->penalty_remarks ?? '',
                'new_remarks' =>  $this->new_remarks ?? '',
                'new_appoint_date' =>  $this->new_appoint_date ?? '',
            ];

            if(!is_null($this->pax_id)){
                return $array;
            }

            $condoitional = [
                'pax' => [
                    'eng_full_name' =>  $this->full_name ?? null,
                    'badge_no' =>  $this->badge_no ?? null,
                    'position' =>     $this->position ?? null,
                    'email' =>     $this->email ?? null,
                    'phone' =>   $this->phone ?? null,
                    'dob' => $this->birthday ?? null,
                    'company' => new CompanyResource($this->company),
                    'department' => new DepartmentResource($this->applicant_department),
                    'latest_passport' => [
                        'passport_country' => new CountryResource($this->passportCountry),
                        'passport_no' => $this->passport_no ?? null,
                        'date_of_issue' => $this->passport_issue_date ?? null,
                        'date_of_expiry' => $this->passport_expiry_date ?? null,
                    ]
                ]
            ];

            return array_merge($array, $condoitional);
    }
}
