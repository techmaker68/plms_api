<?php

namespace Modules\User\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\BaseRepository;
use App\Utils\HttpStatusCode;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\User\Contracts\PLMSUserRepositoryContract;
use Modules\User\Entities\User;

class PLMSUserRepository extends BaseRepository implements PLMSUserRepositoryContract
{
    /**
     * PLMSUsertRepository constructor.
     *
     * @param User $model The Eloquent model.
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function all(array $filters, ?array $withRelations = []): Collection
    {
        return $this->applyQueryFilters($this->getQueryInstance($withRelations), $filters)->get();
    }

    public function index(array $filters, ?array $withRelations = []): LengthAwarePaginator
    {

        return $this->applyQueryFilters($this->getQueryInstance($withRelations), $filters)->paginate($filters['per_page'] ?? 25);
    }

    private function getQueryInstance(?array $withRelations = [])
    {
        return   $this->model->newQuery()->with($withRelations);
    }

    private function applyQueryFilters(Builder $query, array $filters): Builder
    {
        foreach ($filters as $filter => $value) {
            $this->applyFilter($query, $filter, $value, $filters);
        }
        return $query;
    }

    public function applyFilter(Builder &$query,  $filter, $value, $allFilters = null): void
    {
        switch ($filter) {
            case 'search':
                $query->search($value);
                break;
            case 'role_ids':
                $query->whereHas('roles', function ($query) use ($value) {
                    $this->applyConditionalWhere($query, 'id', $value);
                });
                break;
            case 'permission_ids':
                $query->whereHas('permissions', function ($query) use ($value) {
                    $this->applyConditionalWhere($query, 'id', $value);
                });
                break;
            case 'from_roles':
                $query->where('name', '!=', 'Super Admin');
                break;
            case 'sort_by':
                $order = $allFilters['order'] ?? 'desc';
                $query->orderBy($value, $order);
                break;
        }
    }

    public function authenticate($data)
    {
        if (!Auth::attempt($data)) {
            return [
                'code' => HttpStatusCode::UNAUTHORIZED,
                'message' => 'Invalid Email/Password',
            ];
        }
        $user = Auth::user();
        $token = $user->createToken('PLMS')->accessToken;
        $admin = $user->name === 'Super Admin';
        if ($admin) {
            $user->assignRole('Super Admin');
        }
        $user_permissions = $user->getAllPermissions()->map(function ($permission) {
            return [
                'id' => $permission->id,
                'name' => $permission->name,
                'description' => $permission->description,
            ];
        })->toArray();
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'permissions' => $user_permissions,
            'roles' => $user->rolesWithoutPivot()->toArray(),
            'admin' => $admin,
            'token' => $token,
        ];
    }

    public function logout($user)
    {
        $user->token()->revoke();
        return [
            'code' => HttpStatusCode::OK,
            'message' => 'User Logged out successfully',
        ];
    }

    public function getCurrentUser()
    {
        $user = Auth::user();
        if (!$user) {
            return [
                'code' => HttpStatusCode::OK,
                'message' => 'User Not Found',
            ];
        }
        $admin = false;
        if ($user->hasRole('Super Admin')) {
            $admin = true;
        }
        $permissions = $user->getAllPermissions();
        $user_permissions = [];
        foreach ($permissions as $permission) {
            $user_permissions[] = array(
                'id' => $permission->id,
                'name' => $permission->name,
                'description' => $permission->description,
            );
        }
        $return_result = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'permissions' => $user_permissions,
            'roles' => $user->rolesWithoutPivot(),
            'admin' => $admin,
        ];
        return $return_result;
    }

    public function store(array $data): Model
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user = $this->model->create($data);
        if (isset($data['role_ids']) && !empty($data['role_ids'])) {
            $roles = array_map('trim', explode(',', $data['role_ids']));
            foreach ($roles as $role) {
                $user->assignRole($role);
            }
        }
        return $user;
    }

    public function update(int $id, array $data): Model
    {
        $user = User::find($id);
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        $user->update($data);
        if (isset($data['role_ids']) && !empty($data['role_ids'])) {
            $roles = array_map('trim', explode(',', $data['role_ids']));
            $user->syncRoles($roles);
        } else {
            $user->roles()->detach();
        }
        return $user;
    }
}
