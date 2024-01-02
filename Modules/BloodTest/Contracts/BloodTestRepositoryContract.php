<?php

namespace Modules\BloodTest\Contracts;

use Illuminate\Support\Collection;
use App\Contracts\BaseRepositoryContract;

/**
 * Contract for the PLMSPax repository.
 */
interface BloodTestRepositoryContract extends BaseRepositoryContract
{
    public function getDataBetweenDatesForKpiExport($startDate, $endDate);
}