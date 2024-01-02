<?php

namespace Modules\User\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules =  [
            'name' => 'required|string',
            'email' => 'required|email|unique:Modules\User\Entities\User,email',
            'password' => 'required|string|min:8|confirmed',
            'role_ids' => 'nullable|sometimes',
        ];

        if ($this->isMethod('put')) {
            $rules['email'] = 'unique:Modules\User\Entities\User,email,' . $this->route('user');
            $rules['password'] = 'sometimes';
            $rules['role_ids'] = 'sometimes';
        }

        return $rules;

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
