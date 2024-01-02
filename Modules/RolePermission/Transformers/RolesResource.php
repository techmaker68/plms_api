<?php

namespace Modules\RolePermission\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\Transformers\UserResource;
use Spatie\Permission\Contracts\Role;

class RolesResource extends JsonResource
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
            'name' => $this->name ?? '',
            'display_name' => $this->display_name ?? '',
            // 'role_users' => UserResource::collection($this->whenLoaded('users')) ?? [],
            'role_permissions' => PermissionResource::collection($this->permissions) ?? [],
        ];
    }
}
