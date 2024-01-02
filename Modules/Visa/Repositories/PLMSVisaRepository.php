<?php

namespace Modules\Visa\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Visa\Contracts\PLMSVisaRepositoryContract;
use Modules\Visa\Entities\PLMSVisa;

class PLMSVisaRepository extends BaseRepository implements PLMSVisaRepositoryContract
{
    /**
     * PLMSPassportRepository constructor.
     *
     * @param PLMSVisa $model The Eloquent model.
     */
    public function __construct(PLMSVisa $model)
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
            case 'type':
                $this->applyConditionalWhere($query, 'type', $value);
                break;
            case 'department_id':
                $query->whereHas('pax.department', function ($query) use ($value) {
                    $this->applyConditionalWhere($query, 'department_id', $value);
                });
                break;
            case 'date_of_expiry_start':
                $startDate = Carbon::parse($value);
                $endDateValue = $allFilters['date_of_expiry_end'] ?? null;
                $endDate = $endDateValue ? Carbon::parse($endDateValue) : null;
                $query->visaExpiry($startDate, $endDate);
                break;
            case 'company_id':
                $query->whereHas('pax.company', function ($query) use ($value) {
                    $this->applyConditionalWhere($query, 'company_id', $value);
                });
                break;
            case 'status':
                $this->applyConditionalWhere($query, 'status', $value);
                break;
            case 'pax_id':
                $query->where('pax_id', $value);
                break;
            case 'passport_no':
                $query->whereHas('pax.passports', function ($query) use ($value) {
                    $query->where('passport_no', 'LIKE', "%{$value}%");
                });
                break;
            case 'loi_no':
                $query->where('loi_no', $value);
                break;
            case 'no_visa_image':
                if ($value == false) {
                    $query->whereNotNull('file');
                }elseif ($value == true) {
                    $query->whereNull('file');
                }
                break;
            case 'sort_by':
                $order = $allFilters['order'] ?? 'desc';
                $query->orderBy($value, $order);
                break;
        }
    }

    function typeCounts($companyId): array
    {
        // Get visa statuses from the config file
        $visa_statuses = Config('visa.visa_statuses');
        $visaCounts = [];
        foreach ($visa_statuses as $status) {
            $visaCounts[] = ['status' => $status, 'count' => 0];
        }
        $visa_typesCounts = $this->model->select('status', DB::raw('COUNT(*) as count'))
            ->when($companyId, function ($query, $companyId) {
                return $query->whereHas('pax', function ($query) use ($companyId) {
                    $query->where('company_id', $companyId);
                });
            })
            ->groupBy('status')
            ->get();

        foreach ($visa_typesCounts as $visa_typesCount) {
            foreach ($visaCounts as &$visaCount) {
                if ($visaCount['status'] == $visa_typesCount->status) {
                    $visaCount['count'] = $visa_typesCount->count;
                    break;
                }
            }
        }

        return $return_result['visa_types_counts'] = $visaCounts;
    }

    public function updateVisaCancelReason($visa , $data){
        $visa->reason = $data['reason'];
        $visa->status = 5;
        $visa->save();
    }
}
