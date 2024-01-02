<?php

namespace Modules\Department\Services;

use App\Services\BaseService;
use Modules\Department\Contracts\PLMSDepartmentRepositoryContract;
use Modules\Department\Contracts\PLMSDepartmentServiceContract;

class PLMSDepartmentService extends BaseService implements PLMSDepartmentServiceContract
{
  /**
     * PLMSCompanyService constructor.
     *
     * @param PLMSDepartmentRepositoryContract $repository
     */
    public function __construct(PLMSDepartmentRepositoryContract $repository)
    {
        $this->repository = $repository;
    }
    public function typeCounts($companyId): array
    {
        return $this->repository->typeCounts($companyId);
    }
}
