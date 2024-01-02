<?php

namespace Modules\Passport\Services;

use App\Services\BaseService;
use Illuminate\Support\Collection;
use Modules\Passport\Contracts\PLMSPassportServiceContract;
use Modules\Passport\Contracts\PLMSPassportRepositoryContract;
use Modules\Passport\Entities\PLMSPassport;

class PLMSPassportService extends BaseService implements PLMSPassportServiceContract
{
    /**
     * PLMSPaxService constructor.
     *
     * @param PLMSPassportRepositoryContract $repository
     */

    public function __construct(PLMSPassportRepositoryContract $repository)
    {
        parent::__construct($repository);
    }
    
    public function statusCounts($companyId): array
    {
        return $this->repository->statusCounts($companyId);
    }
    
    
}
