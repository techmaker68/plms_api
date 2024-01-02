<?php

namespace Modules\Pax\Transformers;

use App\Resources\CountryResource;
use Modules\Visa\Transformers\VisaResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\BloodTest\Transformers\BloodTestResource;
use Modules\Company\Transformers\CompanyResource;
use Modules\Passport\Transformers\PassportResource;
use Modules\Department\Transformers\DepartmentResource;
use Modules\Loi\Transformers\LoiResource;

class PaxResource extends JsonResource {
    public function toArray($request) {
        $blood_test = $this->getBloodTestDetails();
        $data = [
            'id' => $this->id,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'passports' => PassportResource::collection($this->whenLoaded('passports')),
            'visas' => VisaResource::collection($this->whenLoaded('visas')),
            'lois' => $this->whenLoaded('lois') ? LoiResource::collection($this->lois()->get()): [],
            'blood_tests' =>$this->whenLoaded('blood_tests') ? $blood_test :[],
            'department' => new DepartmentResource($this->whenLoaded('department')),
            'nationality' => new CountryResource($this->whenLoaded('country')),
            'country_residence' => new CountryResource($this->whenLoaded('countryResidence')),
            'country_code' => new CountryResource($this->whenLoaded('countryCode')),
            'latest_loi' => new LoiResource($this->whenLoaded('latestLoi')),
            'latest_visa' => new VisaResource($this->whenLoaded('latestVisa')),
            'latest_passport' => new PassportResource($this->whenLoaded('latestPassport')),
            'latest_blood_test' => new BloodTestResource($this->whenLoaded('latestBloodTest')),
            'pax_id' => $this->pax_id,
            'eng_full_name' => $this->eng_full_name,
            'first_name' => $this->first_name,
            'arab_full_name' => $this->arab_full_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'status' => $this->status,
            'offboard_date' => $this->offboard_date,
            'gender' => $this->gender,
            'dob' => $this->dob,
            'last_name' => $this->last_name,
            'type' => $this->type,
            'badge_no' => $this->badge_no,
            'arab_position' => $this->arab_position,
            'file' => $this->file,
            'position' => $this->position,
            'passport_exist' => $this->passports->isNotEmpty(),
            'visa_exist' => $this->visas->isNotEmpty(),
            'created_at' => $this->created_at
        ];


        return $data;
    }

}
