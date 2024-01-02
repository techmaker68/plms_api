<?php

namespace Modules\User\Contracts;

use App\Contracts\BaseServiceContract;

/**
 * Contract for the Passport service.
 */
interface PLMSUserServiceContract extends BaseServiceContract
{
    public function authenticate($data);
    public function logout($data);
    public function getCurrentUser();
}
