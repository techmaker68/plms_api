<?php

namespace Modules\BloodTest\Http\Requests;

use App\Http\Requests\BaseRequest;
use Modules\BloodTest\Entities\PLMSBloodApplicant;

class BloodApplicantGetRequest extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(PLMSBloodApplicant::class);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'batch_no' => 'required|integer|exists:Modules\BloodTest\Entities\BloodTest,batch_no',
        ]);
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
