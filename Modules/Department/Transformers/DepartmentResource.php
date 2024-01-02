<?php

namespace Modules\Department\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Company\Transformers\CompanyResource;

class DepartmentResource extends JsonResource
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
            'id' => $this->id ?? '',
            'name' => $this->name ?? '',
            'manager_name' => $this->manager_name ?? '',
            'company' => new CompanyResource($this->whenLoaded('company')),
            'abbreviation' => $this->abbreviation ?? '',
        ];
    }
}
