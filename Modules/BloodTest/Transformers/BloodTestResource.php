<?php

namespace Modules\BloodTest\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class BloodTestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'batch_no' => $this->batch_no,
            'submit_date' => $this->submit_date,
            'test_date' => $this->test_date,
            'return_date' => $this->return_date,
            'venue' => $this->venue,
            'applicants_interval' => $this->applicants_interval,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'interval' => $this->interval,
            'submitted' => $this->submitted(),
            'returned' => $this->returned(),
            'tested' => $this->tested(),
        ];
    }
}
