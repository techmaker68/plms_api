<?php

namespace App\Factories;

use App\Contracts\LoiExportContract;
use App\Contracts\KPIServiceContract;
use App\Contracts\BaseServiceContract;
use App\Services\LoiApplicantExportService;
use Modules\Visa\Contracts\PLMSVisaServiceContract;
use Modules\BloodTest\Contracts\BloodApplicantServiceContract;
use Modules\Loi\Contracts\PLMSLoiServiceContract;

class ExportServiceFactory
{
    public static function make(string $type): BaseServiceContract
    {
        switch ($type) {
            case 'visa':
                return resolve(PLMSVisaServiceContract::class);
                break;
            case 'loi':
                return resolve(LoiExportContract::class);
                break;
            case 'blood_penalty':
                return resolve(BloodApplicantServiceContract::class);
                break;
            case 'kpi':
                return resolve(KPIServiceContract::class);
                break;
            case 'blood_test':
                return resolve(BloodApplicantServiceContract::class);
                break;
            case 'loi_applicant_list':
                return resolve(LoiApplicantExportService::class);
                break;
            default:
                throw new \InvalidArgumentException("Invalid Export type: $type");
                break;
        }
    }
}
