<?php

namespace Modules\Company\Transformers;

use App\Resources\CountryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            'country' => new CountryResource($this->whenLoaded('country')),
            'name' => $this->name ?? '',
            'type' => $this->type ?? '',
            'status' => $this->status ?? '',
            'short_name' => $this->short_name ?? '',
            'industry' => $this->industry ?? null,
            'email' => $this->email ?? '',
            'phone' => $this->phone ?? '',
            'website' => $this->website ?? '',
            'city' => $this->city ?? '',
            'poc_name' => $this->poc_name ?? '',
            'poc_email_or_username' => $this->poc_email_or_username ?? '',
            'poc_mobile' => $this->poc_mobile ?? '',
            'address_1' => $this->address_1 ?? '',
            'created_at' => $this->created_at ?? '',
            'paxes' => count($this->paxes) ?? ''
        ];
    }
}
