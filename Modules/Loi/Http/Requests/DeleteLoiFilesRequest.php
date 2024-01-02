<?php

namespace Modules\Loi\Http\Requests;

use App\Rules\BooleanValue;
use Illuminate\Foundation\Http\FormRequest;

class DeleteLoiFilesRequest extends FormRequest
{
    public function rules()
    {
        return [
            'boc_moo_index' => ['nullable','sometimes'],
            'mfd_index' => ['nullable','sometimes'],
            'hq_index' => ['nullable','sometimes'],
            'payment_copy_index' => ['nullable','sometimes'],
            'loi_photo_copy_index' => ['nullable','sometimes'],
        ];
    }
}
