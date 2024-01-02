<?php

namespace Modules\Loi\Contracts;

use Illuminate\Support\Collection;
use App\Contracts\BaseRepositoryContract;

/**
 * Contract for the PLMSLoiApplicant repository.
 */
interface PLMSLoiApplicantRepositoryContract extends BaseRepositoryContract
{
    /**
     * Inserts a batch of records into the database.
     *
     * @param array $applicants An array of applicant data to be inserted.
     * @return bool Returns true if the insert operation was successful, false otherwise.
     */
    public function insert(array $applicants): bool;

    /**
     * Retrieves a collection of applicants associated with a given batch number.
     *
     * @param mixed $batchNo The batch number to filter the applicants.
     * @return Collection Returns a collection of applicants corresponding to the specified batch number.
     */
    public function getApplicantsByBatchNo($batchNo): Collection;

    /**
     * Calculate and return a new Sequence No.
     *
     * @return int
     */
    public function getMaxSequenceNo(): int;

    public function getMaxSequenceNoForBatch(int $batchNo): int;
}