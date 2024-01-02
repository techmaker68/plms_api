<?php

namespace Modules\BloodTest\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Company\Transformers\CompanyResource;
use Modules\Department\Transformers\DepartmentResource;
use Modules\Passport\Transformers\PassportResource;

class BloodApplicantPaxResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'passports' => PassportResource::collection($this->whenLoaded('passports')),
            'company' => new CompanyResource($this->whenLoaded('company')),
            'department' => new  DepartmentResource($this->whenLoaded('department')),
            'full_name' => $this->full_name,
            'pax_id' => $this->pax_id,
            'passport_no' => $this->latestPassport,
            'badge_no' => $this->badge_no,
            'position' => $this->position,
            'department_id' => $this->department_id,
            'department_label' => $this->department_label,
            'email' => $this->email,
            'phone' => $this->phone,
            'employer_label' => $this->employer_label,
            'employer' => $this->employer,
            'birthday' => $this->birthday,
            'blood_test_history' => $this->blood_tests,
        ];
    }
}
