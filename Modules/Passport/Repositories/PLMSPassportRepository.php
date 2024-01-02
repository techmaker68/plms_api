<?php

namespace Modules\Passport\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Passport\Contracts\PLMSPassportRepositoryContract;
use Modules\Passport\Entities\PLMSPassport;

class PLMSPassportRepository extends BaseRepository implements PLMSPassportRepositoryContract
{
    /**
     * PLMSPassportRepository constructor.
     *
     * @param PLMSPassport $model The Eloquent model.
     */
    public function __construct(PLMSPassport $model)
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
            case 'passport_country':
                $this->applyConditionalWhere($query, 'passport_country', $value);
                break;
            case 'country_residence':
                $this->applyConditionalWhere($query, 'place_of_issue', $value);
                break;
            case 'department_id':
                $query->whereHas('pax', function ($query) use ($value) {
                    $this->applyConditionalWhere($query, 'department_id', $value);
                });
                break;
            case 'date_of_expiry_start':
                $startDate = Carbon::parse($value);
                $endDateValue = $allFilters['date_of_expiry_end'] ?? null;
                $endDate = $endDateValue ? Carbon::parse($endDateValue) : null;
                $query->passportExpiry($startDate, $endDate);
                break;
            case 'type':
                $this->applyConditionalWhere($query, 'type', $value);
                break;
            case 'passport_no':
                $query->where('passport_no', 'LIKE', '%' . $value . '%');
                break;
            case 'status':
                $this->applyConditionalWhere($query, 'status', $value);
                break;
            case 'full_name':
                $query->where('full_name', 'LIKE', '%' . $value . '%');
                break;
            case 'pax_id':
                $query->where('pax_id', $value);
                break;
            case 'company_id':
                $query->whereHas('pax', function ($query) use ($value) {
                    $this->applyConditionalWhere($query, 'company_id', $value);
                });
                break;
            case 'no_passport_image':
                if ($value == true) {
                    $query->whereNull('file');
                }elseif ($value == false) {
                    $query->whereNotNull('file');
                }
                break;
            case 'sort_by':
                $order = $allFilters['order'] ?? 'desc';
                $query->orderBy($value, $order);
                break;
        }
    }

    public function statusCounts($company_id = null): array
    {
        // Get passport statuses from the config file
        $passport_statuses = config('passport.passport_statuses');
        $statusCounts = [];
        foreach ($passport_statuses as $status) {
            $statusCounts[] = ['status' => $status, 'count' => 0];
        }

        $query = $this->model->select('status', DB::raw('COUNT(*) as count'));

        // Check if $company_id is provided and not null
        if ($company_id) {
            // Convert $company_id to an array if it's a string
            if (is_string($company_id)) {
                $company_id = explode(',', $company_id);
            }

            // Ensure $company_id is an array
            $company_id = (array) $company_id;

            // Add a condition to the query
            $query->whereHas('pax', function ($query) use ($company_id) {
                $query->whereIn('company_id', $company_id);
            });
        }

        $query->groupBy('status');

        $passportStatusCounts = $query->get();

        foreach ($passportStatusCounts as $passportStatusCount) {
            foreach ($statusCounts as &$statusCount) {
                if ($statusCount['status'] == $passportStatusCount->status) {
                    $statusCount['count'] = $passportStatusCount->count;
                    break;
                }
            }
        }

        return $statusCounts;
    }
}
