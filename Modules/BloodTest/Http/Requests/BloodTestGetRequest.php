<?php

namespace Modules\BloodTest\Http\Requests;

use App\Rules\BooleanValue;
use Modules\BloodTest\Entities\BloodTest;
use App\Http\Requests\BaseRequest;

class BloodTestGetRequest extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(BloodTest::class);
    }


    public function rules()
    {
        return array_merge(parent::rules(), [
            'month' => 'nullable|sometimes|max:255',
            'batch_no' => 'nullable|sometimes|numeric|exist:Modules/BloodTest/Entities/BloodTest,batch_no',
        ]);
    }

    public function authorize()
    {
        return true;
    }
}
