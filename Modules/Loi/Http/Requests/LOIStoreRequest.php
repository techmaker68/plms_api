<?php

namespace Modules\Loi\Http\Requests;

use App\Rules\ValidDate;
use App\Rules\ExistsInModel;
use Modules\Loi\Entities\PLMSLoi;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Loi\Rules\LoiTypeCheck;
use Modules\Loi\Rules\PriorityCheck;
use Modules\Company\Entities\PLMSCompany;
use Modules\Loi\Rules\NationCategoryCheck;

class LOIStoreRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'nation_category' => ['integer', new NationCategoryCheck()],
            'loi_type' => ['integer', new LoiTypeCheck()],
            'submission_date' => ['nullable','sometimes', 'date', new ValidDate],
            'company_id' => ['nullable','sometimes', new ExistsInModel(PLMSCompany::class, 'id')],
            'company_address_iraq_ar' => 'nullable|sometimes|string|max:255',
            'entry_purpose' => 'nullable|sometimes|string|max:255',
            'entry_type' => 'nullable|sometimes|string|max:255',
            'contract_expiry' => ['nullable','sometimes', 'date', new ValidDate],
            'company_address_ar' => 'nullable|sometimes|string', // remove this and add address_arabic column in companies table //Will Discuss with Subtain
            'company_country' => 'nullable|sometimes|exists:App\Models\Country,id',
            'mfd_date' => ['nullable','sometimes', 'date', new ValidDate],
            'mfd_ref' => 'nullable|sometimes|string|max:255',
            'hq_date' => ['nullable','sometimes', 'date', new ValidDate],
            'hq_ref' => 'nullable|sometimes|string|max:255',
            'moo_date' => ['nullable','sometimes', 'date', new ValidDate],
            'moo_ref' => 'nullable|sometimes|string|max:255',
            'boc_moo_date' => ['nullable','sometimes', 'date', new ValidDate],
            'boc_moo_ref' => 'nullable|sometimes|string|max:255',
            'moi_date' => ['nullable','sometimes', 'date', new ValidDate],
            'moi_ref' => 'nullable|sometimes|string|max:255',
            'moi_2_date' => ['nullable','sometimes', 'date', new ValidDate],
            'moi_2_ref' => 'nullable|sometimes|string|max:255',
            'majnoon_date' => ['nullable','sometimes', 'date', new ValidDate],
            'majnoon_ref' => 'nullable|sometimes|string|max:255',
            'payment_copy' => 'nullable|sometimes|file',
            'boc_moo_copy.*' => 'file|max:4096',
            'boc_moo_copy' => 'nullable|sometimes|array',
            'hq_copy.*' => 'file|max:4096',
            'hq_copy' => 'nullable|sometimes|array',
            'mfd_copy.*' => 'file|max:4096',
            'mfd_copy' => 'nullable|sometimes|array',
            'loi_photo_copy' => 'nullable|sometimes|file',
            'moi_payment_date' => ['nullable','sometimes', 'date', new ValidDate],
            'moi_invoice' => 'nullable|sometimes|string|max:255',
            'moi_deposit' => 'nullable|sometimes|string|max:255',
            'loi_issue_date' => ['nullable','sometimes', 'date', new ValidDate],
            'loi_no' => 'nullable|sometimes|integer',
            'sent_loi_date' => ['nullable','sometimes', 'date', new ValidDate],
            'close_date' => ['nullable','sometimes', 'date', new ValidDate],
            'mfd_submit_date' => ['nullable','sometimes', 'date', new ValidDate],
            'mfd_received_date' => ['nullable','sometimes', 'date', new ValidDate],
            'hq_submit_date' => ['nullable','sometimes', 'date', new ValidDate],
            'hq_received_date' => ['nullable','sometimes', 'date', new ValidDate],
            'boc_moo_submit_date' => ['nullable','sometimes', 'date', new ValidDate],
            'moi_payment_letter_date' => ['nullable','sometimes', 'date', new ValidDate],
            'moi_payment_letter_ref' => 'nullable|sometimes|string|max:255',
            'expected_issue_date' => 'nullable|sometimes|string|max:255',
            'priority' => [ 'integer', new PriorityCheck()],
        ];

        if($this->isMethod('put')){
            $rules['batch_no'] = 'nullable|sometimes|integer|unique:Modules\Loi\Entities\PLMSLoi,batch_no,' . $this->route('loi');
        }

        if ($this->isMethod('post')) {
            $rules['nation_category'][] = 'required';
            $rules['loi_type'][] = 'required';
            $rules['priority'][] = 'required';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'batch_no.unique' => 'The batch number already exists. Please try with a unique batch number.',
        ];
    }
}
