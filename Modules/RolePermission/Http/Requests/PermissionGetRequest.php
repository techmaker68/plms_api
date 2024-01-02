<?php

namespace Modules\RolePermission\Http\Requests;

use App\Rules\BooleanValue;
use Illuminate\Foundation\Http\FormRequest;

class PermissionGetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'per_page' => 'sometimes|integer',
            'search' => 'sometimes|string',
            'all' => ['sometimes', new BooleanValue],
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
