<?php

namespace Modules\BloodTest\Services;

use App\Services\BaseService;
use Modules\BloodTest\Contracts\BloodTestServiceContract;
use Modules\BloodTest\Contracts\BloodTestRepositoryContract;
use Illuminate\Database\Eloquent\Model;
use DateTime;

class BloodTestService extends BaseService implements BloodTestServiceContract
{
    /**
     * BloodTestService constructor.
     *
     * @param BloodTestRepositoryContract $repository
     */
    public function __construct(BloodTestRepositoryContract $repository)
    {
        parent::__construct($repository);
    }

    public function store(array $data): Model
    {
        $batch_no = $this->createBatchNo();
        $data['batch_no'] = $batch_no;
        return $this->repository->store($data);
    }

    private function createBatchNo()
    {
        $year = date('y');
        $week = date('W');
        $batch_no = $year . $week;
        return $batch_no;
    }
    
    public function getCurrentWeekData(){

       return  $this->repository->getCurrentWeekBatch();

    }
}
