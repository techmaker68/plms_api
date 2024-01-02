<?php

namespace Modules\Loi\Http\Requests;

use App\Rules\ExistsInModel;
use Modules\Loi\Entities\PLMSLoi;
use App\Http\Requests\BaseRequest;
use Modules\Loi\Rules\PriorityCheck;
use Modules\Loi\Rules\IssuedTypeCheck;
use Modules\Loi\Rules\LoiStatusCheck;
use Modules\Company\Entities\PLMSCompany;

class LOIGetRequest extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(PLMSLoi::class);
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'search_by_applicant' => 'nullable|sometimes|string|max:255',
            'status' => ['nullable','sometimes',new LoiStatusCheck()],
            'company_id' => ['nullable','sometimes', new ExistsInModel(PLMSCompany::class, 'id')],
            'priority' => ['nullable','sometimes', new PriorityCheck()],
            'issued' => ['nullable','sometimes', new IssuedTypeCheck()],
            'batch_nos' => 'nullable|sometimes|string', // addd excel export and and condition or we will do excel export from backend
        ]);
    }
}
