<?php

namespace Modules\Loi\Contracts;

use Illuminate\Support\Collection;
use App\Contracts\BaseServiceContract;
use Illuminate\Database\Eloquent\Model;

/**
 * Contract for the PLMSLoi service.
 */
interface PLMSLoiServiceContract extends BaseServiceContract
{
    /**
     * Calculate and return a new Batch No.
     *
     * @return int
     */
    public function calculateBatchNo(): int;

    /**
     * Renew Loi and return array of loi applcants.
     *
     * @return int
     */
    public function renewLOI(int $batchNo): Model;

    /**
     * Calculate and return a new Sequence No.
     *
     * @return int
     */
    public function getMaxSequenceNo(): int;

    public function getMaxSequenceNoForBatch(int $batchNo): int;

    public function loiApplicantall(array $data): Collection;
}

