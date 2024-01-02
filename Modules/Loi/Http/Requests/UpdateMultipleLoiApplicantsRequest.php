<?php

namespace Modules\Loi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMultipleLoiApplicantsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'ids' => 'required|string',
            'deposit_amount' => 'nullable|sometimes|numeric',
            'loi_payment_date' => 'nullable|sometimes|date',
            'loi_payment_receipt_no' => 'nullable|sometimes|string',
            'remarks' => 'nullable|sometimes|string',
            'status' => 'nullable|sometimes|integer',
            'payment_letter_copies.*' => 'file|max:4096',
            'payment_letter_copies' =>'nullable|sometimes|array',
        ];
    }
}
