<?php

namespace Modules\Company\Http\Requests;

use App\Http\Requests\BaseRequest;
use Modules\Company\Entities\PLMSCompany;
use Modules\Company\Rules\ValidCompanyStatus;
use Modules\Company\Rules\ValidCompanyType;

class CompanyRequest extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(PLMSCompany::class);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'name' => 'nullable|sometimes|string|max:255',
            'type' => ['nullable','sometimes', new ValidCompanyType],
            'status' => ['nullable','sometimes', 'integer', new ValidCompanyStatus],
            'short_name' => 'nullable|sometimes|string|max:255',
            'industry' => 'nullable|sometimes|string|max:255',
            'email' => 'nullable|sometimes|email|max:255|exists:Modules\Company\Entities\PLMSCompany,email',
            'phone' => 'nullable|sometimes|string|max:20||exists:Modules\Company\Entities\PLMSCompany,phone',
            'website' => 'nullable|sometimes|url|max:255',
            'city' => 'nullable|sometimes|string|max:255',
            'country_id' => 'nullable|sometimes|integer|exists:app\Models\Country,id',
            'poc_name' => 'nullable|sometimes|string|max:255',
            'poc_email_or_username' => 'nullable|sometimes|string|max:255',
            'poc_mobile' => 'nullable|sometimes|integer',
            'address_1' => 'nullable|sometimes|string|max:255',
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
