<?php

namespace App\Repositories;


use App\Contracts\ExcelImportRepositoryContract;
use Modules\BloodTest\Entities\PLMSBloodApplicant;
use Modules\Loi\Entities\PLMSLoiApplicant;
use Modules\Loi\Entities\PLMSLoi;
use Modules\Passport\Entities\PLMSPassport;
use Modules\Pax\Entities\PLMSPax;
use Modules\Visa\Entities\PLMSVisa;

/**
 * Abstract base repository class.
 */
class ExcelImportRepository implements ExcelImportRepositoryContract
{
    protected $paxModel;
    protected $passportModel;
    protected $VisaModel;
    protected $loiModel;
    protected $loiApplicantModel;
    protected $bloodApplicantModel;

    public function __construct(
        PLMSPax $paxModel,
        PLMSPassport $passportModel,
        PLMSVisa $VisaModel,
        PLMSLoiApplicant $loiApplicantModel,
        PLMSLoi $loiModel,
        PLMSBloodApplicant $bloodApplicantModel
    ) {
        $this->paxModel = $paxModel;
        $this->passportModel = $passportModel;
        $this->VisaModel = $VisaModel;
        $this->loiApplicantModel = $loiApplicantModel;
        $this->loiModel = $loiModel;
        $this->bloodApplicantModel = $bloodApplicantModel;
    }

    public function findByBadgeNo($badgeNo)
    {
        return $this->paxModel->where('badge_no', $badgeNo)->first();
    }

    public function findByPassportNo($passportNo)
    {
        return $this->passportModel->where('passport_no', str_replace(' ', '', $passportNo))->first();
    }

    public function findByVisaNo($visaNo)
    {
        return $this->VisaModel->where('visa_no', $visaNo)->first();
    }

    public function findByBatchNo($batchNo)
    {
        return $this->loiModel->where('batch_no', $batchNo)->first();
    }

    public function findByBatchAndPax($batch, $pax)
    {
        return $this->loiApplicantModel->where('batch_no', $batch)->where('pax_id', $pax)->first();
    }

    public function findBypassportNoAndBatchNo($passport, $batch)
    {
        return $this->bloodApplicantModel->where('passport_no', $passport)->where('batch_no', $batch)->first();
    }
}
