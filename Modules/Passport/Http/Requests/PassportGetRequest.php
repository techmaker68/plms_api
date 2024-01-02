<?php

namespace Modules\Passport\Http\Requests;

use App\Http\Requests\BaseRequest;
use App\Rules\BooleanValue;
use App\Rules\ExistsInModel;
use App\Rules\ValidDate;
use App\Models\Country;
use Modules\Company\Entities\PLMSCompany;
use Modules\Department\Entities\PLMSDepartment;
use Modules\Passport\Entities\PLMSPassport;
use Modules\Passport\Rules\ValidPassportStatus;
use Modules\Passport\Rules\ValidPassportType;

class PassportGetRequest extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(PLMSPassport::class);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'status' => ['nullable','sometimes', 'max:255', 'string', new ValidPassportStatus],
            'date_of_expiry_start' => ['nullable','sometimes', 'date', new ValidDate],
            'date_of_expiry_end' => ['nullable','sometimes', 'date', new ValidDate, 'after_or_equal:date_of_expiry_start'],
            'no_passport_image' => ['nullable','sometimes', new BooleanValue],
            'type' => ['nullable','sometimes',  new ValidPassportType],
            'passport_no' => ['nullable','sometimes', 'string', 'max:255', new ExistsInModel(PLMSPassport::class, 'passport_no')],
            'passport_country' => ['nullable','sometimes', new ExistsInModel(Country::class, 'id')],
            'department_id' => ['nullable','sometimes', new ExistsInModel(PLMSDepartment::class, 'id')],
            'company_id' => ['nullable','sometimes', new ExistsInModel(PLMSCompany::class, 'id')],
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
