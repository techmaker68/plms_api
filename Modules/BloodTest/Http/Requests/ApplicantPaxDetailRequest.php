<?php

namespace Modules\BloodTest\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicantPaxDetailRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer|exists:Modules\Pax\Entities\PLMSPax,pax_id',
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
