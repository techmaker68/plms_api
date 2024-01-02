<?php

namespace Modules\Loi\Http\Requests;

use App\Rules\ExistsInModel;
use Modules\Pax\Entities\PLMSPax;
use App\Http\Requests\BaseRequest;
use Modules\Loi\Entities\PLMSLoiApplicant;

class SaveLoiApplicantsBulkRequest extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(PLMSLoiApplicant::class);
    }
    public function rules()
    {
        return array_merge(parent::rules(), [
            'batch_no' => 'required|exists:Modules\Loi\Entities\PLMSLoi,batch_no',
            'pax_ids' => ['required', new ExistsInModel(PLMSPax::class, 'pax_id')],
        ]);
    }
}
