<?php

namespace Modules\RolePermission\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Modules\User\Entities\User;
use App\Utils\HttpStatusCode;
use Illuminate\Support\Facades\Validator;
use Modules\RolePermission\Http\Requests\RoleStoreRequest;
use Modules\RolePermission\Http\Requests\UpdateUsersRoleRequest;
use Modules\RolePermission\Http\Requests\RemoveRoleRequest;
use Modules\RolePermission\Http\Requests\RoleUpdateRequest;
use Modules\RolePermission\Http\Requests\AssginRoleToUserRequest;
use Modules\RolePermission\Http\Requests\PermissionsUpdateRequest;
use Modules\RolePermission\Transformers\PermissionResource;
use Modules\RolePermission\Transformers\RolesResource;
use Modules\User\Transformers\UserResource;

class RoleController extends BaseController
{

    public function index(Request $request)
    {
        return $this->tryCatch(function () use ($request) {
           $roles = Role::with('users:id,name,email', 'permissions:id,name,description')
                ->select('id', 'name', 'display_name');
            if ($request->input('search')) {
                $search = $request->input('search');
                $roles = $roles->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('display_name', 'LIKE', "%{$search}%");
                });
                // return response()->json($roles);
            }
            $roles = $roles->where('name', '!=', 'Super Admin')->paginate($request->input('per_page', 25));
            return RolesResource::collection($roles)->response();
        });
    }

    public function store(RoleStoreRequest $request)
    {
        return $this->tryCatch(function () use ($request) {
            $request->validated();
            $role =  Role::create(['name' => $request->name, 'display_name' => $request->display_name, 'guard_name' => 'api']);
            foreach ($request->selectedPermissions as  $permission) {
                $permission = Permission::findById($permission);
                $permission->assignRole($role);
            }
            return (new RolesResource($role))->response();
        });
    }

    public function update(RoleUpdateRequest $request, $id)
    {
        return $this->tryCatch(function () use ($request, $id) {
            $role = Role::find($id);
            $role->update([
                'name' => $request->name,
                'display_name' => $request->display_name,
            ]);
            $role->permissions()->sync($request->selectedPermissions);
            return (new RolesResource($role))->response();
        });
    }

    public function assignRoleToUser(AssginRoleToUserRequest $request)
    { 
        return $this->tryCatch(function () use ($request) {
            $role = Role::findById($request->id);
                $currentUsers = User::role($role)->get();
                foreach ($currentUsers as $user) {
                    $user->removeRole($role);
                }
                if ($request->user_id) {
                    $userIds = explode(',', $request->user_id);
                    $users = User::whereIn('id',$userIds)->get();
                    foreach ($userIds as $userId) {
                        $user = User::findOrFail($userId);
                        $user->assignRole($role);
                    }
                }
                return   UserResource::collection($users)->response();
        });
    }

    public function removeRoleFromUser(RemoveRoleRequest $request)
    {
        return $this->tryCatch(function () use ($request) {
            $role = Role::findById($request->role_id);
            $user =  User::find($request->user_id);
            $user->removeRole($role);
            return (new UserResource($user))->response();
        });
    }

    public function usersWithRoles(Request $request)
    {
        return $this->tryCatch(function () use ($request) {
            $users = new User();
            if ($request->input('search')) {
                $search = $request->input('search');
                $users = $users->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('user_name', 'LIKE', "%{$search}%")
                        ->orWhereHas('roles', function ($companyQuery) use ($search) {
                            $companyQuery->where('name', 'LIKE', "%{$search}%");
                        });
                });
            }
            $users = $users->paginate($request->input('per_page'));
            return UserResource::collection($users)->response();
        });
    }

    public function getUserWithRole($id)
    {
        $this->validateId($id, 'Modules\User\Entities\User');
        return $this->tryCatch(function () use ($id) {
            $user = User::find($id);
            if ($user) {
                return (new UserResource($user))->response();
            } else {
                return response()->json(['message' => 'Record not found'], 204);
            }
        });
    }

    public function userByRole($id)
    {
        $this->validateId($id, 'Spatie\Permission\Models\Role');
        return $this->tryCatch(function () use ($id) {
            $role = Role::find($id);
            if ($role) {
                return (new  RolesResource($role))->response();
            } else {
                return response()->json(['message' => 'Record not found'], 200);
            }
        });
    }

    public function show($id)
    {
        $this->validateId($id, 'Spatie\Permission\Models\Role');
        return $this->tryCatch(function () use ($id) {
            $role = Role::find($id);
            if ($role) {
                return (new  RolesResource($role))->response();
            } else {
                return response()->json(['message' => 'Record not found'], 200);
            }
        });
    }

    public function destroy($id)
    {
        $this->validateId($id, 'Spatie\Permission\Models\Role');
        return $this->tryCatch(function () use ($id) {
            $role = Role::find($id);
            if ($role) {
                $role->delete();
                return response()->json(['message' => 'Record deleted successsfully'], 200);
            } else {
                return response()->json(['message' => 'Record not found'], 204);
            }
        });
    }

    public function roleList(Request $request)
    {
        return $this->tryCatch(function () use ($request) {
            $roles = new Role();
            if ($request->input('search')) {
                $search = $request->input('search');
                $roles = $roles->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('display_name', 'LIKE', "%{$search}%");
                });
            }
            $roles = $roles->where('name', '!=', 'Super Admin')->paginate($request->input('per_page', 25));
            $resourceCollection = RolesResource::collection($roles);
            return $resourceCollection->response();
        });
    }

    public function updateUserRole(UpdateUsersRoleRequest $request)
    { // confusion
        return $this->tryCatch(function () use ($request) {
            $user = User::find($request->user_id);
            $user->syncRoles($request->selected_roles);
            return (new UserResource($user))->response();
        });
    }
}
