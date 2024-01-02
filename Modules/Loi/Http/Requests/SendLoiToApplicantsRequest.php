<?php

namespace Modules\Loi\Http\Requests;

use App\Rules\BooleanValue;
use Illuminate\Foundation\Http\FormRequest;

class SendLoiToApplicantsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'batch_no' => 'required|exists:Modules\Loi\Entities\PLMSLoiApplicant,batch_no',
            'attachment.*' => 'file|max:20480',
            'to' => 'nullable|sometimes|string',
            'bcc' => 'nullable|sometimes|string',
            'cc' => 'nullable|sometimes|string',
            'department_managers' => 'nullable|sometimes|string',
            'loi_focal_points' => 'nullable|sometimes|string',
            'subject' => 'required|string',
            'content' => 'required|string',
            'all_applicants' => ['nullable','sometimes', new BooleanValue]
        ];
    }
}
