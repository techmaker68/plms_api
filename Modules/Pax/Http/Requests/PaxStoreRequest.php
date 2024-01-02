<?php

namespace Modules\Pax\Http\Requests;

use App\Rules\ValidDate;
use App\Rules\DigitsLength;
use Modules\Pax\Rules\TypeCheck;
use Modules\Pax\Rules\PaxStatusCheck;
use Illuminate\Foundation\Http\FormRequest;

class PaxStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'type' => ['sometimes', 'string', 'max:255', new TypeCheck],
            'first_name' => ['sometimes','string','max:255'],
            'last_name' => ['sometimes','string', 'max:255'],
            'company_id' => ['sometimes','integer','exists:Modules\Company\Entities\PLMSCompany,id'],
            'badge_no' => ['nullable', new DigitsLength(6), 'unique:Modules\Pax\Entities\PLMSPax,badge_no'],
            'eng_full_name' => ['sometimes', 'string', 'max:255'],
            'arab_full_name' => 'nullable|sometimes|string|max:255',
            'nationality' => 'nullable|sometimes|integer|exists:App\Models\Country,id',
            'position' => 'nullable|sometimes|string|max:255',
            'country_residence' => 'nullable|sometimes|integer|exists:App\Models\Country,id',
            'arab_position' => 'nullable|sometimes|string|max:255',
            'department_id' => 'nullable|sometimes|integer|exists:Modules\Department\Entities\PLMSDepartment,id',
            'email' => ['sometimes','email','unique:Modules\Pax\Entities\PLMSPax,email'],
            'dob' => ['nullable','sometimes', 'date', 'before:today', new ValidDate],
            'phone' => 'nullable|sometimes|string|max:255',
            'country_code_id' => 'nullable|sometimes|integer|exists:App\Models\Country,id',
            'gender' => ['sometimes','max:255','in:Male,Female'],
            'status' => ['nullable','sometimes', new PaxStatusCheck],
            'file' => 'nullable|sometimes|image|mimes:jpeg,png,jpg,gif,svg',
        ]; 

        if ($this->input('status') && $this->input('status') == '2') {
            $rules['offboard_date'] = ['required', new ValidDate];
        }

        if ($this->isMethod('put')) {
            $rules['badge_no'] = 'nullable|unique:Modules\Pax\Entities\PLMSPax,badge_no,' . $this->route('paxis');
            $rules['email'] = 'nullable|unique:Modules\Pax\Entities\PLMSPax,email,' . $this->route('paxis');
        }
        if ($this->isMethod('post')) {
            $rules['type'][] = 'required';
            $rules['first_name'][] = 'required';
            $rules['last_name'][] = 'required';
            $rules['company_id'][] = 'required';
            $rules['eng_full_name'][] = 'required';
            $rules['email'][] = 'required';
            $rules['gender'][] = 'required';
        }

        return $rules;
    }
}
