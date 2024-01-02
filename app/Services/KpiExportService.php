<?php

namespace App\Services;

use App\Contracts\KPIServiceContract;
use Illuminate\Database\Eloquent\Collection;
use Modules\BloodTest\Contracts\BloodTestRepositoryContract;

class KpiExportService extends BaseService implements KPIServiceContract
{
    /**
     * KpiExportService constructor.
     *
     * @param BloodTestRepositoryContract $repository
     */
    public function __construct(BloodTestRepositoryContract $repository)
    {
        parent::__construct($repository);
    }

    public function all(array $data, ?array $withRelations = []): Collection
    {
        return $this->repository->getDataBetweenDatesForKpiExport($data['start_date'], $data['end_date']);
    }
}
