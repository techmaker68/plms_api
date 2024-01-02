<?php

namespace Modules\BloodTest\Http\Requests;
use App\Rules\ExistsInModel;
use Illuminate\Foundation\Http\FormRequest;
use Modules\BloodTest\Entities\PLMSBloodApplicant;

class PenaltyReportGenerateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'batch_no' =>  ['required', new ExistsInModel(PLMSBloodApplicant::class, 'batch_no')],
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
