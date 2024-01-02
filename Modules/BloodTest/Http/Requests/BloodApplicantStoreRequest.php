<?php

namespace Modules\BloodTest\Http\Requests;

use App\Rules\ValidDate;
use Illuminate\Foundation\Http\FormRequest;

class BloodApplicantStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'arrival_date' => ['nullable','sometimes', new ValidDate],
            'attendance' => 'nullable|sometimes',
            'badge_no' => 'nullable|sometimes|integer',
            'batch_no' => 'required|integer|exists:Modules\BloodTest\Entities\BloodTest,batch_no',
            'departure_date' => ['nullable','sometimes', new ValidDate],
            'hbs_expire_date' => ['nullable','sometimes', new ValidDate],
            'hiv_expire_date' => ['nullable','sometimes', new ValidDate],
            'passport_no' => 'required_without:pax_id|string|unique:Modules\Passport\Entities\PLMSPassport,passport_no',
            'passport_return_date' => ['nullable','sometimes', new ValidDate],
            'passport_submit_date' => ['nullable','sometimes', new ValidDate],
            'pax_id' => 'nullable|sometimes|exists:Modules\Pax\Entities\PLMSPax,pax_id',
            'remarks' => 'nullable|sometimes|string',
            'task_purposes' => 'nullable|sometimes',
            'birthday' => ['nullable','sometimes','before:today', new ValidDate],
            'passport_country' => 'nullable|sometimes|integer',
            'full_name' => 'required_without:pax_id|string',
            'employer' => 'nullable|sometimes|exists:Modules\Company\Entities\PLMSCompany,id',
            'position' => 'nullable|sometimes|string|max:255',
            'department' => 'nullable|sometimes|integer|exists:Modules\Department\Entities\PLMSDepartment,id',
            'email' => 'required_without:pax_id|email',
            'phone' => 'nullable|sometimes|integer',
            'country_code_id' => 'nullable|sometimes|integer|exists:App\Models\Country,id',
            'blood_test_types' => 'nullable|sometimes',
            'passport_issue_date' => ['nullable','sometimes', new ValidDate],
            'passport_expiry_date' => ['nullable','sometimes', new ValidDate],
        ];

        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
