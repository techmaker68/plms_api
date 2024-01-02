<?php

namespace Modules\User\Http\Requests;

use App\Http\Requests\BaseRequest;
use Modules\User\Entities\User;

class UserRequest extends BaseRequest
{
    public function __construct()
    {
        parent::__construct(User::class);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            'name' => 'nullable|sometimes|string',
            'email' => 'email',
        ]);
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
