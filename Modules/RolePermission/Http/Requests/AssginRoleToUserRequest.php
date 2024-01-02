<?php

namespace Modules\RolePermission\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Rules\ExistsInModel;
use Modules\User\Entities\User;

class AssginRoleToUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => ['required', new ExistsInModel(Role::class, 'id')],
            'user_id' => ['required', new ExistsInModel(User::class, 'id')]
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
