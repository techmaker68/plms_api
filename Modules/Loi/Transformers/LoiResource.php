<?php

namespace Modules\Loi\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Company\Transformers\CompanyResource;

class LoiResource extends JsonResource
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
            'applicants' => LoiApplicantResource::collection($this->whenLoaded('applicants')),
            'applicants_count' => $this->applicants->count() ?? '',
            'nation_category' => $this->nation_category,
            'batch_no' => $this->batch_no,
            'loi_type' => $this->loi_type,
            'submission_date' => $this->submission_date,
            'mfd_date' => $this->mfd_date,
            'mfd_ref' => $this->mfd_ref,
            'moo_date' => $this->moo_date,
            'moo_ref' => $this->moo_ref,
            'hq_date' => $this->hq_date,
            'hq_ref' => $this->hq_ref,
            'boc_moo_date' => $this->boc_moo_date,
            'boc_moo_ref' => $this->boc_moo_ref,
            'moi_date' => $this->moi_date,
            'moi_ref' => $this->moi_ref,
            'moi_2_date' => $this->moi_2_date,
            'moi_2_ref' => $this->moi_2_ref,
            'majnoon_ref' => $this->majnoon_ref,
            'majnoon_date' => $this->majnoon_date,
            'moi_payment_date' => $this->moi_payment_date,
            'payment_copy' => $this->payment_copy,
            'mfd_copy' => $this->mfd_copy,
            'hq_copy' => $this->hq_copy,
            'boc_moo_copy' => $this->boc_moo_copy,
            'loi_photo_copy' => $this->loi_photo_copy,
            'moi_invoice' => $this->moi_invoice,
            'moi_deposit' => $this->moi_deposit,
            'loi_issue_date' => $this->loi_issue_date,
            'loi_no' => $this->loi_no,
            'sent_loi_date' => $this->sent_loi_date,
            'close_date' => $this->close_date,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'entry_type' => $this->entry_type,
            'mfd_submit_date' => $this->mfd_submit_date,
            'entry_purpose' => $this->entry_purpose,
            'contract_expiry' => $this->contract_expiry,
            'priority' => $this->priority,
            'mfd_received_date' => $this->mfd_received_date,
            'hq_submit_date' => $this->hq_submit_date,
            'hq_received_date' => $this->hq_received_date,
            'boc_moo_submit_date' => $this->boc_moo_submit_date,
            'moi_payment_letter_date' => $this->moi_payment_letter_date,
            'moi_payment_letter_ref' => $this->moi_payment_letter_ref,
            'expected_issue_date' => $this->expected_issue_date,
            'company_address_iraq_ar' => $this->company_address_iraq_ar,
            'company_address_ar' => $this->company_address_ar,
            'status' => $this->status,
        ];
    }
}
