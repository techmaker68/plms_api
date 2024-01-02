<?php

namespace App\Sorters;

use App\Contracts\SortableModelContract;
use Modules\Loi\Entities\PLMSLoiApplicant;

class LoiApplicantSorter implements SortableModelContract
{
    public function sort(array $ids): void
    {
        foreach ($ids as $index => $id) {
            $model = PLMSLoiApplicant::find($id);
            $model->sequence_no = $index + 1;
            $model->save();
        }
    }
}
