<?php

namespace Modules\Department\Http\Requests;

use App\Http\Requests\BaseRequest;
use Modules\Department\Entities\PLMSDepartment;

class DepartmentRequest extends BaseRequest
{

    public function __construct()
    {
        parent::__construct(PLMSDepartment::class);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'name' => 'nullable|sometimes|string|max:255',
            'company_id' => 'nullable|sometimes|integer|exists:Modules\Company\Entities\PLMSCompany,id',
            'manager_name' => 'nullable|sometimes|string|max:255',
            'abbreviation' => 'nullable|sometimes|string|max:255',
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
