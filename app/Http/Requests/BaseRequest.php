<?php

namespace App\Http\Requests;

use App\Rules\BooleanValue;
use App\Rules\ColumnExists;
use Modules\Loi\Entities\PLMSLoi;
use Modules\Pax\Entities\PLMSPax;
use Modules\User\Entities\User;
use Modules\Visa\Entities\PLMSVisa;
use Modules\BloodTest\Entities\BloodTest;
use Modules\Company\Entities\PLMSCompany;
use Illuminate\Foundation\Http\FormRequest;
use Modules\BloodTest\Entities\PLMSBloodApplicant;
use Modules\Passport\Entities\PLMSPassport;
use Modules\Department\Entities\PLMSDepartment;
use Modules\Loi\Entities\PLMSLoiApplicant;

class BaseRequest extends FormRequest
{
    protected $model;

    public function __construct($model)
    {
        parent::__construct();

        $this->model = $model;
    }
    /**
     * Retrieve the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules()
    {
        // Define the allowed models for validation
        $allowedModels = [
            PLMSPax::class,
            PLMSLoiApplicant::class,
            BloodTest::class,
            PLMSPassport::class,
            PLMSVisa::class,
            PLMSCompany::class,
            PLMSDepartment::class,
            User::class,
            PLMSLoi::class,
            PLMSBloodApplicant::class,
        ];

        // Check if the provided model is valid
        if (!in_array($this->model, $allowedModels)) {
            throw new \InvalidArgumentException("Invalid model for validation");
        }

        // Get the table name of the model
        $tableName = (new $this->model)->getTable();

        // Define the common validation rules
        return [
            'per_page' => 'sometimes|integer|min:1|max:100',
            'sort_by' => ['sometimes', 'string', new ColumnExists($tableName)],
            'order' => 'sometimes|in:asc,desc',
            'all' => ['sometimes', new BooleanValue],
            'search' => 'sometimes|string|max:255',
        ];
    }
}
