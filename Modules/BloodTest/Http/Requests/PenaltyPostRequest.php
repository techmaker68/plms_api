<?php

namespace Modules\BloodTest\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenaltyPostRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'penalty_fee' => 'required|integer',
            'penalty_remarks' => 'required|string',
            'type' => 'required|string',
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
