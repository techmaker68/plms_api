<?php

namespace Modules\BloodTest\Http\Requests;

use App\Rules\ExistsInModel;
use App\Rules\ValidDate;
use Illuminate\Foundation\Http\FormRequest;
use Modules\BloodTest\Entities\PLMSBloodApplicant;

class BloodApplicantBulkUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'applicant_ids' =>  ['required', 'string', new ExistsInModel(PLMSBloodApplicant::class, 'id')],
            'blood_test_types' => 'nullable|sometimes',
            'arrival_date' => ['nullable','sometimes', new ValidDate],
            'departure_date' => ['nullable','sometimes', new ValidDate],
            'remarks' => 'nullable|sometimes|string',
            'attendance' => 'nullable|sometimes',
            'task_purposes' => 'nullable|sometimes|array',
            'appoint_time' => 'nullable|sometimes',
            'appoint_date' => ['nullable','sometimes', new ValidDate],
            'hiv_expire_date' => ['nullable','sometimes', new ValidDate],
            'hbs_expire_date' => ['nullable','sometimes', new ValidDate],
            'passport_submit_date' => ['nullable','sometimes', new ValidDate],
            'passport_return_date' => ['nullable','sometimes', new ValidDate],
        ];
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
