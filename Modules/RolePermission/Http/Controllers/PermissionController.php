<?php

namespace Modules\RolePermission\Http\Controllers;

use App\Http\Controllers\BaseController;
use Modules\User\Entities\User;
use Spatie\Permission\Models\Permission;
use Modules\RolePermission\Http\Requests\PermissionCheckRequest;
use Modules\RolePermission\Http\Requests\PermissionGetRequest;
use Modules\RolePermission\Http\Requests\PermissionStoreRequest;
use Modules\RolePermission\Http\Requests\PermissionUpdateRequest;
use Modules\RolePermission\Transformers\PermissionResource;

class PermissionController extends BaseController
{
    public function index(PermissionGetRequest $request)
    {
        return $this->tryCatch(function () use ($request) {
            $request->validated();
            $permissions = new  Permission();
            if ($request->input('search')) {
                $search = $request->input('search');
                $permissions = $permissions->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                });
            }
            if ($request->all) {
                $permissions = $permissions->get();
            } else {
                $permissions = $permissions->paginate($request->input('per_page'));
            }
            return PermissionResource::collection($permissions)->response();
        });
    }

    public function store(PermissionStoreRequest $request)
    {
        return $this->tryCatch(function () use ($request) {
            $request->validated();
            $permission =  Permission::create(['name' => $request->name, 'description' => $request->description, 'guard_name' => 'api']);
            return (new PermissionResource($permission))->response();
        });
    }


    public function update(PermissionUpdateRequest $request, $id)
    {
        return $this->tryCatch(function () use ($request, $id) {
            $request->validated();
            $permission =  Permission::findById($id);
            $permission->update([
                'name' => $request->name,
                'description' => $request->description,
                'guard_name' => 'api',
            ]);
            return (new PermissionResource($permission))->response();
        });
    }

    public function show($id)
    {
        return $this->tryCatch(function () use ($id) {
            $permission =  Permission::findById($id);
            return (new PermissionResource($permission))->response();
        });
    }

    public function user_permissions(User $user)
    {
        return $this->tryCatch(function () use ($user) {
            if ($user) {
                $permissions = $user->getAllPermissions();
                $permissionNames = $permissions->pluck('name')->toArray();
                return response()->json(['permissions' => $permissionNames], 200);
            } else {
                return response()->json(['permissions' => []], 200);
            }
        });
    }

    public function check_permission(PermissionCheckRequest $request)
    {
        return $this->tryCatch(function () use ($request) {
            $request->validated();
            $user = User::find($request->user_id);
            $permission = Permission::find($request->permission_id);
            $is_assigned = $user->hasPermissionTo($permission);
            return response()->json(['status' => $is_assigned], 200);
        });
    }

    public function destroy($id)
    {
        return $this->tryCatch(function () use ($id) {
            $permission = Permission::find($id);
            if ($permission) {
                $permission->delete();
                return response()->json(['message' => 'Record deleted successfully'], 200);
            } else {
                return response()->json(['message' => 'Record not found'], 200);
            }
        });
    }
}
