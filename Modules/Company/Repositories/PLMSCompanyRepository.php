<?php

namespace Modules\Company\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Company\Contracts\PLMSCompanyRepositoryContract;
use Modules\Company\Entities\PLMSCompany;

class PLMSCompanyRepository extends BaseRepository implements PLMSCompanyRepositoryContract
{
    /**
     * PLMSCompanytRepository constructor.
     *
     * @param PLMSCompany $model The Eloquent model.
     */
    public function __construct(PLMSCompany $model)
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
                $query->searchByName($value);
                break;
            case 'sort_by':
                $order = $allFilters['order'] ?? 'desc';
                $query->orderBy($value, $order);
                break;
        }
    }
}
