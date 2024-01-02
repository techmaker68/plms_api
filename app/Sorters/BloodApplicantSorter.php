<?php

namespace App\Sorters;

use Modules\BloodTest\Entities\PLMSBloodApplicant;
use App\Contracts\SortableModelContract;

class BloodApplicantSorter implements SortableModelContract
{
    public function sort(array $ids): void
    {
        $batch = null;
        foreach ($ids as $index => $id) {
            $model = PLMSBloodApplicant::find($id);
            $model->sequence_no = $index + 1;
            $model->save();
            $batch = $model->blood_test;
        }
        if($batch != null){
            $batch->processBatch($batch->batch_no);
        }
    }
}
