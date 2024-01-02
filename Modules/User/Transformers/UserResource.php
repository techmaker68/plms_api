<?php

namespace Modules\User\Transformers;

use Modules\User\Entities\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\RolePermission\Transformers\RolesResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'roles' => RolesResource::collection($this->roles),
            'admin' => $this->hasRole('Super Admin') ? true : false,
        ];
    }
}
