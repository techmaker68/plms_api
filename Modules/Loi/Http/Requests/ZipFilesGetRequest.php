<?php

namespace Modules\Loi\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ZipFilesGetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'batch_no' => 'required|exists:Modules\Loi\Entities\PLMSLoi,batch_no',
            'file' => 'required|file|mimes:doc,docx',
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
