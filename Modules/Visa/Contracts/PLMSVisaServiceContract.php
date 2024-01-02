<?php

namespace Modules\Visa\Contracts;

use App\Contracts\BaseServiceContract;

/**
 * Contract for the Passport service.
 */
interface PLMSVisaServiceContract extends BaseServiceContract
{
    public function typeCounts($companyId): array;

    /**
     * Prepare Relations to be loaded
     *
     * @return array having relation names to be loaded.
     */
    public function prepareRelations(array &$filters): array;
}
