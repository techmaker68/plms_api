<?php

namespace Modules\Visa\Http\Requests;

use App\Http\Requests\BaseRequest;
use App\Rules\BooleanValue;
use App\Rules\ExistsInModel;
use App\Rules\ValidDate;
use Modules\Company\Entities\PLMSCompany;
use Modules\Department\Entities\PLMSDepartment;
use Modules\Visa\Entities\PLMSVisa;
use Modules\Visa\Rules\ValidVisaStatus;
use Modules\Visa\Rules\ValidVisaType;

class VisaGetRequest extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(PLMSVisa::class);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'department_id' =>  ['nullable','sometimes', new ExistsInModel(PLMSDepartment::class, 'id')],
            'date_of_expiry_start' => ['nullable','sometimes', 'date', new ValidDate],
            'date_of_expiry_end' => ['nullable','sometimes', 'date', new ValidDate, 'after_or_equal:date_of_expiry_start'],
            'company_id' => ['nullable','sometimes', new ExistsInModel(PLMSCompany::class, 'id')],
            'status' => ['nullable','sometimes', new ValidVisaStatus],
            'passport_no' => 'nullable|sometimes|string|exists:Modules\Passport\Entities\PLMSPassport,passport_no',
            'pax_id' => 'nullable|sometimes|integer|exists:Modules\Pax\Entities\PLMSPax,pax_id',
            'type' => ['nullable','sometimes', new ValidVisaType],
            'no_visa_image' => ['nullable','sometimes', new BooleanValue],
            'latest_passport' => ['nullable','sometimes', new BooleanValue],
        ]);
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
