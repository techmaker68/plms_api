<?php

namespace Modules\RolePermission\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionCheckRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|numeric|exists:Modules\User\Entities\User,id',
            'permission_id' => 'required|numeric|exists:plms_permissions,id',
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
