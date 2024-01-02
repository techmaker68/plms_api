<?php

namespace Modules\Passport\Transformers;

use App\Resources\CountryResource;
use Modules\Pax\Transformers\PaxResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PassportResource extends JsonResource
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
            'passport_country' => new CountryResource($this->whenLoaded('countryOfPassport')),
            'place_of_issue' => new CountryResource($this->whenLoaded('countryOfIssue')),
            'passport_no' => $this->passport_no ?? null,
            'date_of_issue' => $this->date_of_issue ?? null,
            'file' => $this->file,
            'passport_image' => $this->file != null ? true : false,
            'date_of_expiry' => $this->date_of_expiry ?? null,
            'status_id' => $this->checkPassportStatusByDays($this->date_of_expiry),
            'status' => $this->getPassportStatusString($this->checkPassportStatusByDays($this->date_of_expiry)),
            'full_name' => $this->full_name ?? null,
            'arab_full_name' => $this->arab_full_name ?? null,
            'birthday' => $this->birthday ?? null,
            'type' => $this->type,
            'expire_in_days' => $this->date_of_expiry ? daysPassedAndRemaining($this->date_of_expiry)['remaining'] : null,
        ];
    }
}
