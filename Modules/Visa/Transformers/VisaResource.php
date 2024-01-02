<?php

namespace Modules\Visa\Transformers;

use Modules\Pax\Transformers\PaxResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Department\Transformers\DepartmentResource;
use Modules\Passport\Transformers\PassportResource;
use Modules\Loi\Transformers\LoiResource;

class VisaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'pax' => new PaxResource($this->whenLoaded('pax')),
            'latest_passport' => new PassportResource($this->whenLoaded('latestPassport')),
            'type' => $this->type ?? '',
            'file' => $this->file,
            'visa_image' => $this->file != null ? true : false,
            'date_of_issue' => $this->date_of_issue,
            'date_of_expiry' => $this->date_of_expiry,
            'visa_location' => $this->visa_location,
            'visa_no' => $this->visa_no,
            'loi' => new LoiResource($this->whenLoaded('loi')),
            'passport' => new PassportResource($this->whenLoaded('passport')),
            'expire_in_days' => $this->date_of_expiry ? daysPassedAndRemaining($this->date_of_expiry)['remaining'] : null,
            'reason' => $this->reason ? html_entity_decode($this->reason) : null,
            'status_id' => $this->checkVisaStatusByDays($this->date_of_expiry),
            'status' => $this->getVisaStatusString($this->checkVisaStatusByDays($this->date_of_expiry)),
        ];
    }
}
