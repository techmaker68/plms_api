<?php

namespace Modules\Visa\Http\Requests;

use App\Rules\ValidDate;
use Modules\Visa\Rules\ValidVisaType;
use Illuminate\Foundation\Http\FormRequest;

class VisaStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'pax_id' => ['required','integer','exists:Modules\Pax\Entities\PLMSPax,pax_id'],
            'loi_id' => ['sometimes','exists:Modules\Loi\Entities\PLMSLoi,id'],
            'passport_id' => ['sometimes','exists:Modules\Passport\Entities\PLMSPassport,id'],
            'type' => ['sometimes',  new ValidVisaType],
            'date_of_issue' => ['sometimes', 'date', new ValidDate],
            'date_of_expiry' => ['sometimes', 'date', new ValidDate, 'after_or_equal:date_of_expiry'],
            'visa_no' => 'required|integer|unique:Modules\Visa\Entities\PLMSVisa,visa_no',
            'visa_location' => 'nullable|sometimes|string|max:255',
            'file' => 'nullable|sometimes|file|max:' . config('filesystems.max_file_size'),
        ];
        if ($this->isMethod('put')) {
            $rules['visa_no'] = 'nullable|sometimes|unique:Modules\Visa\Entities\PLMSVisa,visa_no,' . $this->route('visa');
        }

        if ($this->isMethod('post')) {
            $rules['passport_id'][] = 'required';
            $rules['loi_id'][] = 'required';
            $rules['type'][] = 'required';
            $rules['date_of_issue'][] = 'required';
            $rules['date_of_expiry'][] = 'required';
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
