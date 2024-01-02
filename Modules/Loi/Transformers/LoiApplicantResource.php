<?php

namespace Modules\Loi\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Pax\Transformers\PaxResource;


class LoiApplicantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $last_loi_applicant = $this->lastLoiApplicantByPax($this->batch_no, $this->pax_id) ?? null;
        return [
            'id' => $this->id,
            'pax' => new PaxResource($this->whenLoaded('pax')),
            'batch_no' => $this->batch_no,
            'status' => $this->status,
            'loi_payment_date' => $this->loi_payment_date,
            'deposit_amount' => $this->deposit_amount,
            'loi_payment_receipt_no' => $this->loi_payment_receipt_no,
            'remarks' => $this->generateRemarks(),
            'sequence_no' => $this->sequence_no,
            'payment_letter_copy' => $this->payment_letter_copy,
            'loi_photo_copy' => $this->loi_photo_copy,
            'loi_no' => $this->loi_no,
            'loi_issue_date' => $this->loi_issue_date,
            'last_loi' => $last_loi_applicant->batch_no ?? '',
            'receipt_no' => $last_loi_applicant->loi_payment_receipt_no ?? '',
            'paid_date' => $last_loi_applicant->loi_payment_date ?? '',
        ];
    }
}
