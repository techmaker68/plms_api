<?php

namespace Modules\Loi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeletePaymentLetterCopyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'index' => 'required|integer',
        ];
    }
}
