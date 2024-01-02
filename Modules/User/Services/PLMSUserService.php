<?php

namespace Modules\User\Services;

use App\Services\BaseService;
use Modules\User\Contracts\PLMSUserRepositoryContract;
use Modules\User\Contracts\PLMSUserServiceContract;

class PLMSUserService extends BaseService implements PLMSUserServiceContract
{
    /**
     * PLMSCompanyService constructor.
     *
     * @param PLMSUserRepositoryContract $repository
     */
    public function __construct(PLMSUserRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    public function authenticate($data)
    {
        return  $this->repository->authenticate($data);
    }
    public function logout($data)
    {
        return  $this->repository->logout($data);
    }
    public function getCurrentUser()
    {
        return  $this->repository->getCurrentUser();
    }
    public function registerUser($data)
    {
        return  $this->repository->registerUser($data);
    }
    public function updateUser($id, $data)
    {
        return  $this->repository->updateUser($id, $data);
    }
}
