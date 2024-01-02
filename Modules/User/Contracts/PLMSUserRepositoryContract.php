<?php

namespace Modules\User\Contracts;

use App\Contracts\BaseRepositoryContract;

/**
 * Contract for the Passport service.
 */
interface PLMSUserRepositoryContract extends BaseRepositoryContract
{
    public function authenticate($authenticate);
    public function logout($authenticate);
    public function getCurrentUser();
}
