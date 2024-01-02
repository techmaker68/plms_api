<?php

namespace Modules\BloodTest\Http\Requests;

use App\Rules\ExistsInModel;
use Modules\BloodTest\Entities\PLMSBloodApplicant;
use Illuminate\Foundation\Http\FormRequest;

class RemoveBloodApplicantRequest extends FormRequest
{
    public function rules()
    {
        return [
            'ids' => ['required', new ExistsInModel(PLMSBloodApplicant::class, 'id')],
        ];
    }
}
