<?php

namespace Modules\Visa\Contracts;

use App\Contracts\BaseRepositoryContract;

/**
 * Contract for the Passport service.
 */
interface PLMSVisaRepositoryContract extends BaseRepositoryContract
{
    public function typeCounts($companyId): array;
}
