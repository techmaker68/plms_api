<?php

namespace Modules\Company\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Company\Rules\ValidCompanyStatus;
use Modules\Company\Rules\ValidCompanyType;

class CompanyStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => ['nullable','sometimes', 'max:255', new ValidCompanyType],
            'status' => ['nullable','sometimes', 'max:255', new ValidCompanyStatus],
            'short_name' => 'nullable|sometimes|string|max:255',
            'industry' => 'nullable|sometimes|string|max:255',
            'email' => 'nullable|sometimes|email|max:255|unique:Modules\Company\Entities\PLMSCompany,email',
            'phone' => 'nullable|sometimes|string|max:20|unique:Modules\Company\Entities\PLMSCompany,phone',
            'website' => 'nullable|sometimes|max:255',
            'city' => 'nullable|sometimes|string|max:255',
            'country_id' => 'nullable|sometimes|integer|exists:App\Models\Country,id',
            'poc_name' => 'nullable|sometimes|string|max:255',
            'poc_email_or_username' => 'nullable|sometimes|string|max:255',
            'poc_mobile' => 'nullable|sometimes|integer',
            'address_1' => 'nullable|sometimes|string|max:255',
        ];

        if ($this->isMethod('put')) {
            $rules['email'] = 'required|unique:Modules\Company\Entities\PLMSCompany,email,' . $this->route('company');
            $rules['phone'] = 'required|unique:Modules\Company\Entities\PLMSCompany,phone,' . $this->route('company');
        }
        if ($this->isMethod('post')) {
            $rules['status'][] = 'required';
        }
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
