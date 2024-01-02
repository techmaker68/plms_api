<?php

namespace Modules\Loi\Http\Requests;

use App\Rules\ExistsInModel;
use Modules\Loi\Entities\PLMSLoi;
use Modules\Pax\Entities\PLMSPax;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Loi\Entities\PLMSLoiApplicant;

class LoiApplicantStoreRequest extends FormRequest
{
    public function rules()
    {
        $rules =  [
            'batch_no' => ['integer', new ExistsInModel(PLMSLoi::class, 'batch_no')],
            'pax_id' => [ 'integer', new ExistsInModel(PLMSPax::class, 'pax_id')],
            'remarks' => 'nullable|sometimes|string|max:1000',
            'deposit_amount' => 'nullable|sometimes|numeric',
            'loi_payment_date' => 'nullable|sometimes|date',
            'loi_payment_receipt_no' => 'nullable|sometimes|string|max:255',
            'status' => 'nullable|sometimes|integer',
            'payment_letter_copy' => 'nullable|sometimes|array',
            'payment_letter_copy.*' => 'file|mimes:jpg,jpeg,png,pdf',
        
        ];

        if ($this->isMethod('post')) {
            $rules['batch_no'][] = 'required';
            $rules['pax_id'][] = 'required';
        }
        return $rules;
    }
}
