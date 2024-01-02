<?php

namespace Modules\Pax\Contracts;

use Illuminate\Support\Collection;
use App\Contracts\BaseServiceContract;

/**
 * Contract for the PLMSPax service.
 */
interface PLMSPaxServiceContract extends BaseServiceContract
{
    /**
     * Get counts of different types based on the company ID.
     *
     * @param int|null $companyId Optional company ID to filter the counts.
     * @return Collection The collection of type counts.
     */
    public function typeCounts($companyId): Collection;

    /**
     * Get counts of different statuses.
     *
     * @return array Associative array with status counts.
     */
    public function statusCounts(): array;

    /**
     * Calculate and return a new Pax ID.
     *
     * @return int The calculated Pax ID.
     */
    public function calculatePaxId(): int;

    /**
     * Prepare Relations to be loaded
     *
     * @return array having relation names to be loaded.
     */
    public function prepareRelations(array &$filters): array;
}

