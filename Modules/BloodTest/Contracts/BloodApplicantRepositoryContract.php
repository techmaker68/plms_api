<?php

namespace Modules\BloodTest\Contracts;

use App\Contracts\BaseRepositoryContract;

/**
 * Contract for the PLMSBloodApplicant repository.
 */
interface BloodApplicantRepositoryContract extends BaseRepositoryContract
{

    public function updateApplicantsInBulk(int $id, array $data);
    public function rescheduleApplicant($data);
    // public function renewAppointment(?int $id, $data);
    // public function addPenalty(?int $id, $data);
    // public function sendApplicantsNote($data);
    public function getPaxDetail($id);
}
