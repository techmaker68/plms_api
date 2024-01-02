<?php

namespace Modules\Loi\Http\Requests;

use App\Rules\ExistsInModel;
use Modules\Loi\Entities\PLMSLoiApplicant;
use Illuminate\Foundation\Http\FormRequest;

class RemoveLoiApplicantRequest extends FormRequest
{
    public function rules()
    {
        return [
            'ids' => ['required', new ExistsInModel(PLMSLoiApplicant::class, 'id')],
        ];
    }
}
