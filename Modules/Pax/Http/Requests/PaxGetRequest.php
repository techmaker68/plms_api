<?php

namespace Modules\Pax\Http\Requests;

use App\Rules\ValidDate;
use App\Rules\BooleanValue;
use App\Rules\ExistsInModel;
use Modules\Pax\Rules\TypeCheck;
use Modules\Pax\Entities\PLMSPax;
use Modules\Pax\Rules\RouteCheck;
use App\Http\Requests\BaseRequest;
use App\Models\Country;
use Modules\Company\Entities\PLMSCompany;
use Modules\Pax\Rules\BatchNoCheck;
use Modules\Pax\Rules\PaxStatusCheck;
use Modules\Pax\Rules\NationCategoryCheck;
use Modules\Department\Entities\PLMSDepartment;

class PaxGetRequest extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(PLMSPax::class);
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
            'country_residence' => ['nullable','sometimes', new ExistsInModel(Country::class, 'id')],
            'position' => 'nullable|sometimes|string|max:255',
            'visa_expiry_start' => ['nullable','sometimes', 'date', new ValidDate],
            'visa_expiry_end' => ['nullable','sometimes', 'date', new ValidDate, 'after_or_equal:visa_expiry_start'],
            'route' => ['nullable','sometimes', 'string', new RouteCheck],
            'nationality' => ['nullable','sometimes', new ExistsInModel(Country::class, 'id')],
            'department_id' => ['nullable','sometimes', new ExistsInModel(PLMSDepartment::class, 'id')],
            'type' => ['nullable','sometimes', 'string', new TypeCheck],
            'status' => ['nullable','sometimes', 'string', new PaxStatusCheck],            
            'company_id' => ['nullable','sometimes', new ExistsInModel(PLMSCompany::class, 'id')],
            'nation_category' => ['nullable','sometimes', 'string', new NationCategoryCheck],
            'pax_without_passport' => ['nullable','sometimes', new BooleanValue],
            'pax_without_visa' => ['nullable','sometimes', new BooleanValue],
            'pax_without_badge' => ['nullable','sometimes', new BooleanValue],
            'similar_name' => ['nullable','sometimes', new BooleanValue],
            'batch_no' => ['nullable','sometimes', 'numeric', new BatchNoCheck($this->input('route'))],
            'latest_loi' => ['nullable','sometimes', new BooleanValue],
            'latest_visa' => ['nullable','sometimes', new BooleanValue],
            'latest_passport' => ['nullable','sometimes', new BooleanValue],
            'latest_blood_test' => ['nullable','sometimes', new BooleanValue],
        ]);
    }
}
