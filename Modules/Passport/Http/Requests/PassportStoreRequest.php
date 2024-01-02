<?php

namespace Modules\Passport\Http\Requests;

use App\Http\Requests\BaseRequest;
use App\Rules\ValidDate;
use Modules\Passport\Rules\ValidPassportType;
use Illuminate\Foundation\Http\FormRequest;

class PassportStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'full_name' => 'required|string|max:255',
            'pax_id' => 'required|integer|exists:Modules\Pax\Entities\PLMSPax,pax_id',
            'passport_no' => 'required|string|max:255|unique:Modules\Passport\Entities\PLMSPassport,passport_no',
            'date_of_issue' => ['required', 'date', new ValidDate],
            'date_of_expiry' => ['required', 'date', new ValidDate, 'after_or_equal:date_of_issue'],
            'birthday' => ['required', 'date', 'before:today', new ValidDate],
            'passport_country' => 'required|integer|exists:App\Models\Country,id',
            'place_of_issue' => 'required|integer|exists:App\Models\Country,id',
            'type' => ['required', 'max:255', new ValidPassportType],
            'file' => 'nullable|sometimes|file|max:' . config('filesystems.max_file_size'),
        ];
        if ($this->isMethod('put')) {
            $rules['passport_no'] = 'required|unique:Modules\Passport\Entities\PLMSPassport,passport_no,' . $this->route('passport');
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
