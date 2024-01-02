<?php

namespace App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'country_code_2' => $this->country_code_2,
            'country_code_3' => $this->country_code_3,
            'calling_code' => $this->calling_code,
            'region_code' => $this->region_code,
            'subregion_code' => $this->subregion_code,
            'intermediate_region_code' => $this->intermediate_region_code,
            'country_name_short_en' => $this->country_name_short_en,
            'country_name_full_en' => $this->country_name_full_en,
            'country_name_short_local' => $this->country_name_short_local,
            'country_name_full_local' => $this->country_name_full_local,
            'country_name_short_zh_cn' => $this->country_name_short_zh_cn,
            'country_name_full_zh_cn' => $this->country_name_full_zh_cn,
            'country_name_short_ar' => $this->country_name_short_ar,
            'country_name_full_ar' => $this->country_name_full_ar,
            'nationality_en' => $this->nationality_en,
            'nationality_zh_cn' => $this->nationality_zh_cn,
            'nationality_ar' => $this->nationality_ar,
            'remarks' => $this->remarks,
        ];
    }
}