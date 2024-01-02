<?php

namespace Modules\Pax\Contracts;

use Illuminate\Support\Collection;
use App\Contracts\BaseRepositoryContract;

/**
 * Contract for the PLMSPax repository.
 */
interface PLMSPaxRepositoryContract extends BaseRepositoryContract
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
     * @return int
     */
    public function getMaxPaxId(): int;
}