<?php

namespace Modules\Passport\Contracts;

use Illuminate\Support\Collection;
use App\Contracts\BaseServiceContract;

/**
 * Contract for the Passport service.
 */
interface PLMSPassportServiceContract extends BaseServiceContract
{
    public function statusCounts($companyId): array;
}
