<?php

namespace Modules\Pax\Repositories;

use Carbon\Carbon;
use Modules\Pax\Entities\PLMSPax;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Pax\Contracts\PLMSPaxRepositoryContract;

class PLMSPaxRepository extends BaseRepository implements PLMSPaxRepositoryContract
{
    /**
     * PLMSPaxRepository constructor.
     *
     * @param PLMSPax $model The Eloquent model.
     */
    public function __construct(PLMSPax $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all the PLMS pax records.
     *
     * @param array $filters The filters to apply.
     * @param array|null $withRelations The relations to eager load.
     * @return Collection The collection of PLMS pax records.
     */
    public function all(array $filters, ?array $withRelations = []): Collection
    {
        return $this->applyQueryFilters($this->getQueryInstance($withRelations), $filters)->get();
    }

    /**
     * Get the paginated PLMS pax records.
     *
     * @param array $filters The filters to apply.
     * @param array|null $withRelations The relations to eager load.
     * @return LengthAwarePaginator The paginated collection of PLMS pax records.
     */
    public function index(array $filters, ?array $withRelations = []): LengthAwarePaginator
    {
        return $this->applyQueryFilters($this->getQueryInstance($withRelations), $filters)->paginate($filters['per_page'] ?? 25);
    }

    /**
     * Get a new instance of the query builder with eager loaded relations.
     *
     * @param array|null $withRelations The relations to eager load.
     * @return Builder The query builder instance.
     */
    private function getQueryInstance(?array $withRelations = []): Builder
    {
        $query = $this->model->newQuery();
    
        // Check if 'latestLoi' should be loaded
        if (in_array('latestLoi', $withRelations)) {
            $query->with(['latestLoi' => function ($query) {
                // Define a subquery to load the latest PLMSLoi
                $query->orderByDesc('plms_lois.created_at')
                    ->limit(1);
            }]);
    
            // Remove 'latestLoi' from the original relations array to avoid duplicate loading
            $withRelations = array_diff($withRelations, ['latestLoi']);
        }
    
        // Load other relations normally
        if (!empty($withRelations)) {
            $query->with($withRelations);
        }
    
        return $query;
    }

    /**
     * Apply the query filters to the query builder.
     *
     * @param Builder $query The query builder instance.
     * @param array $filters The filters to apply.
     * @return Builder The modified query builder instance.
     */
    private function applyQueryFilters(Builder $query, array $filters): Builder
    {
        foreach ($filters as $filter => $value) {
            $this->applyFilter($query, $filter, $value, $filters);
        }
        return $query;
    }

    /**
     * Apply a specific filter to the query builder.
     *
     * @param Builder $query The query builder instance.
     * @param string $filter The filter to apply.
     * @param mixed $value The value of the filter.
     * @param array|null $allFilters All the filters.
     * @return void
     */
    public function applyFilter(Builder &$query, string $filter, $value, $allFilters = null): void
    {
        switch ($filter) {
            case 'search':
                $query->search($value);
                break;
            case 'nationality':
                $this->applyConditionalWhere($query, 'nationality', $value);
                break;
            case 'country_residence':
                $this->applyConditionalWhere($query, 'country_residence', $value);
                break;
            case 'department_id':
                $this->applyConditionalWhere($query, 'department_id', $value);
                break;
            case 'position':
                $query->position($value);
                break;
            case 'pax_without_passport':
                if ($value) {
                    $query->withoutPassport();
                }
                break;
            case 'pax_without_visa':
                if ($value) {
                    $query->withoutVisa();
                }
                break;
            case 'pax_without_badge':
                if ($value) {
                    $query->withoutBadge();
                }
                break;
            case 'similar_name':
                if ($value) {
                    $query->similarName();
                }
                break;
            case 'visa_expiry_start':
                $startDate = Carbon::parse($value);
                $endDateValue = $allFilters['visa_expiry_end'] ?? null;
                $endDate = $endDateValue ? Carbon::parse($endDateValue) : null;

                $query->visaExpiry($startDate, $endDate);
                break;
            case 'type':
                $query->types($value);
                break;
            case 'status':
                $this->applyConditionalWhere($query, 'status', $value);
                break;
            case 'company_id':
                $this->applyConditionalWhere($query, 'company_id', $value);
                break;
            case 'nation_category':
                $query->nationCategory($value);
                break;
            case 'sort_by':
                $order = $allFilters['order'] ?? 'desc';
                $query->orderBy($value, $order);
                break;
            case 'route':
                $query->routeFilter($value, $allFilters['batch_no'] ?? null);
                break;
        }
    }

    /**
     * Get the type counts of PLMS pax records.
     *
     * @param int|null $companyId The company ID.
     * @return Collection The collection of type counts.
     */
    public function typeCounts($companyId = null): Collection
    {
        return $this->model->select('type', \DB::raw('COUNT(*) as count'))
                    ->when($companyId, function ($query, $companyId) {
                        // Convert $companyId to an array if it's a string
                        if (is_string($companyId)) {
                            $companyId = explode(',', $companyId);
                        }

                        // Ensure $companyId is an array
                        $companyId = (array) $companyId;

                        // Add a condition to the query
                        return $query->whereIn('company_id', $companyId);
                    })
                    ->groupBy('type')
                    ->get();
    }

    /**
     * Get the status counts of PLMS pax records.
     *
     * @return array The array of status counts.
     */
    public function statusCounts(): array
    {
        return [
            'onboard' => $this->model->where('status', 1)->count(),
            'offboard' => $this->model->where('status', 2)->count(),
        ];
    }

    /**
     * Get the maximum pax ID.
     *
     * @return int The maximum pax ID.
     */
    public function getMaxPaxId(): int
    {
        return $this->model->max('pax_id') ?: 0;
    }
}
