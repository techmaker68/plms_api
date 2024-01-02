<?php

namespace Modules\Visa\Http\Requests;

use App\Rules\ValidDate;
use Modules\Visa\Rules\ValidVisaType;
use Illuminate\Foundation\Http\FormRequest;

class VisaCancelRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'reason' => 'required|string|max:10000',
        ];

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
