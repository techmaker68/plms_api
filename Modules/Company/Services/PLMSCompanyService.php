<?php

namespace Modules\Company\Services;

use App\Services\BaseService;
use Modules\Company\Contracts\PLMSCompanyServiceContract;
use Modules\Company\Contracts\PLMSCompanyRepositoryContract;

class PLMSCompanyService extends BaseService implements PLMSCompanyServiceContract
{
  /**
     * PLMSCompanyService constructor.
     *
     * @param PLMSCompanyRepositoryContract $repository
     */
    public function __construct(PLMSCompanyRepositoryContract $repository)
    {
        $this->repository = $repository;
    }
    public function typeCounts($companyId): array
    {
        return $this->repository->typeCounts($companyId);
    }
}
