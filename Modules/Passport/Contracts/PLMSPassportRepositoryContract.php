<?php

namespace Modules\Passport\Contracts;

use Illuminate\Support\Collection;
use App\Contracts\BaseRepositoryContract;

/**
 * Contract for the Passport service.
 */
interface PLMSPassportRepositoryContract extends BaseRepositoryContract
{
    public function statusCounts($companyId): array;
}
