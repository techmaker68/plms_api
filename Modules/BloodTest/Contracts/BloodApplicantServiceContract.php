<?php

namespace Modules\BloodTest\Contracts;

use App\Contracts\BaseServiceContract;


/**
 * Contract for the PLMSPax service.
 */
interface BloodApplicantServiceContract extends BaseServiceContract
{
    public function updateApplicantsInBulk($data);
    public function rescheduleApplicant($data);
    public function renewAppointment(?int $id, $data);
    public function addPenalty(?int $id, $data);
    public function sendApplicantsNote($data);
    public function getPaxDetail($id);
}
