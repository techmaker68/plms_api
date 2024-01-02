<?php

namespace Modules\BloodTest\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Modules\BloodTest\Entities\BloodTest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\BloodTest\Entities\PLMSBloodApplicant;
use Modules\BloodTest\Contracts\BloodTestRepositoryContract;

class BloodTestRepository extends BaseRepository implements BloodTestRepositoryContract
{
    /**
     * BloodTestRepository constructor.
     *
     * @param BloodTest $model The Eloquent model.
     */
    public function __construct(BloodTest $model)
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

    private function getQueryInstance(?array $withRelations = []): Builder
    {
        return $this->model->newQuery()->with($withRelations);
    }

    private function applyQueryFilters(Builder $query, array $filters): Builder
    {
        foreach ($filters as $filter => $value) {
            $this->applyFilter($query, $filter, $value, $filters);
        }
        return $query;
    }

    public function applyFilter(Builder &$query, string $filter, $value, $allFilters = null): void
    {
        if (empty($value) && $filter !== 'sort_by') {
            return;
        }

        switch ($filter) {
            case 'search':
                $query->search($value);
                break;

            case 'month':
                $query->filterByDateRange($value);
                break;

            case 'batch_no':
                $query->where('batch_no', $value);
                break;

            case 'sort_by':
                $order = $allFilters['order'] ?? 'desc';
                $sortBy = $value ?? 'batch_no';
                $query->orderBy($sortBy, $order);
                break;
        }
    }

    public function getCurrentWeekBatch(){

        $year = date('y'); // get last 2 digits of year
        // Check if today is Sunday
        if (date('D') == 'Sun') {
            // Add 7 days to get to next Sunday and determine its week number
            $referenceDate = strtotime('+7 days');
        } else {
            $referenceDate = time(); // Use the current date
        }
        
        $week = date('W', $referenceDate);
        
        // Combine year and week to your desired format
        $batch_no = $year . $week;
        $current_batch = BloodTest::where('batch_no', $batch_no)->first();
        $current_week_batch = $current_batch ? true : false;
        return [
            'current_week_batch' => $current_week_batch,
            'batch_no' => $batch_no,
            'submit_date' => date('Y-m-d', strtotime('Sunday this week')),
            'test_date' => date('Y-m-d', strtotime('Monday this week')),
            'return_date' => date('Y-m-d', strtotime('Tuesday this week')),
        ];
    }

    public function getDataBetweenDatesForKpiExport($startDate, $endDate)
    {
        return $this->model->whereBetween('test_date', [$startDate, $endDate])->get();   
    }
}
