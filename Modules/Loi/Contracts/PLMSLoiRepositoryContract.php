<?php

namespace Modules\Loi\Contracts;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\BaseRepositoryContract;

/**
 * Contract for the PLMSLoi repository.
 */
interface PLMSLoiRepositoryContract extends BaseRepositoryContract
{
    /**
     * Calculate and return a new Batch No.
     *
     * @return int
     */
    public function getMaxBatchNo(): int;

    /**
     * Get old record through Bathc No.
     *
     * @return int
     */
    public function getOldBatchRecord(int $oldBatchNo): ?Model;
    
    /**
     * Create New record from old Bathc No.
     *
     * @return int
     */
    public function createNewBatchFromOld($oldLOI, int $oldBatchNo): Model;
}