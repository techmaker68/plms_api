<?php

namespace App\Services;

use App\Contracts\LoiApplicantServiceContract;
use Illuminate\Database\Eloquent\Collection;
use Modules\Loi\Contracts\PLMSLoiApplicantRepositoryContract;

class LoiApplicantExportService extends BaseService implements LoiApplicantServiceContract
{
    /**
     * KpiExportService constructor.
     *
     * @param PLMSLoiApplicantRepositoryContract $repository
     */
    public function __construct(PLMSLoiApplicantRepositoryContract $repository)
    {
        parent::__construct($repository);
    }

    public function all(array $data, ?array $withRelations = []): Collection
    {
        return $this->repository->all($data, $withRelations);
    }
}
