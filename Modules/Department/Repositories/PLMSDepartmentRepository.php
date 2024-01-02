<?php

namespace Modules\Department\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Department\Contracts\PLMSDepartmentRepositoryContract;
use Modules\Department\Entities\PLMSDepartment;

class PLMSDepartmentRepository extends BaseRepository implements PLMSDepartmentRepositoryContract
{
    /**
     * PLMSDepartmenttRepository constructor.
     *
     * @param PLMSDepartment $model The Eloquent model.
     */
    public function __construct(PLMSDepartment $model)
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
                $query->searchDetails($value);
                break;
            case 'sort_by':
                $order = $allFilters['order'] ?? 'desc';
                $query->orderBy($value, $order);
                break;
        }
    }
}
