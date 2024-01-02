<?php

namespace App\Services;

use App\Contracts\LoiExportContract;
use Illuminate\Database\Eloquent\Collection;
use Modules\Loi\Repositories\PLMSLoiApplicantRepository;

class LoiExportService extends BaseService implements LoiExportContract
{
    /**
     * LoiExportService constructor.
     *
     * @param PLMSLoiApplicantRepository $repository
     */
    public function __construct(PLMSLoiApplicantRepository $repository)
    {
        parent::__construct($repository);
    }

    public function all(array $data, ?array $withRelations = []): Collection
    {
        return $this->repository->getExportData($data['batch_no']);
    }
}
