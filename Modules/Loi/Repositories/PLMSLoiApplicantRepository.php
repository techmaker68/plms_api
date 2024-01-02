<?php

namespace Modules\Loi\Repositories;

use App\Models\PLMSSetting;
use App\Traits\HandleFiles;
use Illuminate\Support\Arr;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\Loi\Entities\PLMSLoiApplicant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Loi\Contracts\PLMSLoiApplicantRepositoryContract;

class PLMSLoiApplicantRepository extends BaseRepository implements PLMSLoiApplicantRepositoryContract
{
    use HandleFiles;
    /**
     * PLMSLoiRepository constructor.
     *
     * @param PLMSLoiApplicant $model The Eloquent model.
     */
    public function __construct(PLMSLoiApplicant $model)
    {
        parent::__construct($model);
        $this->initialize();
    }

    public function initialize()
    {
        $this->model->setSequenceNumberForAll();
    }

    public function all(array $filters, ?array $withRelations = []): Collection
    {
        return $this->applyQueryFilters($this->getQueryInstance($withRelations), $filters)->orderBy('sequence_no', 'asc')->get();
    }

    public function index(array $filters, ?array $withRelations = []): LengthAwarePaginator
    {
        return $this->applyQueryFilters($this->getQueryInstance($withRelations), $filters)->paginate($filters['per_page'] ?? 25);
    }

    private function getQueryInstance(?array $withRelations = []): Builder
    {
        return $this->model->newQuery()->with($withRelations);
    }

    private function applyQueryFilters(Builder $query, array $filters): Builder
    {
        foreach ($filters as $filter => $value) {
            $this->applyFilter($query, $filter, $value, $filters);
        }
        return $query;
    }

    public function applyFilter(Builder &$query, string $filter, $value, $allFilters = null): void
    {
        switch ($filter) {
            case 'batch_no':
                $query->where('batch_no', $value);
        }
    }

    /**
     * Inserts a batch of records into the database.
     *
     * @param array $applicants An array of applicant data to be inserted.
     * @return bool Returns true if the insert operation was successful, false otherwise.
     */
    public function insert(array $applicants): bool
    {
        return $this->model->insert($applicants);
    }

    /**
     * Retrieves a collection of applicants associated with a given batch number.
     *
     * This method fetches all applicants from the database that are linked to a specific batch number.
     * It is useful for retrieving all new applicants created as a result of a batch operation like renewing LOI.
     *
     * @param mixed $batchNo The batch number to filter the applicants.
     * @return Collection Returns a collection of applicants corresponding to the specified batch number.
     */
    public function getApplicantsByBatchNo($batchNo): Collection
    {
        return $this->model->where('batch_no', $batchNo)->get();
    }

    public function getMaxSequenceNo(): int
    {
        return $this->model->max('sequence_no') ?: 0;
    }

    public function getMaxSequenceNoForBatch($batchNo): int
    {
        return $this->model->where('batch_no', $batchNo)->max('sequence_no') ?: 0;
    }

    public function getApplicantsByIds($ids): Collection
    {
        if (is_array($ids)) {
            return $this->model->whereIn('id', $ids)->get();
        } else {
            return $this->model->where('id', $ids)->get();
        }
    }

    public function deleteApplicantFiles($applicants)
    {
        $applicants->each(function ($applicant) {
            $this->unlinkFiles($applicant->payment_letter_copy);
            $this->unlinkFiles($applicant->loi_photo_copy);
        });
    }

    public function deletePaymentLetter($applicant, $index) : array
    {
        $files = $this->getFilesArray($applicant->payment_letter_copy);

        if (isset($files[$index]) && $files[$index] !== "") {
            $this->deleteFileAndUpdateArray($files, $index);
            $applicant->payment_letter_copy = implode(',', $files);
            $applicant->save();
            return ['status'=> true , 'message'=>'File removed Successfully.'];
        }
        return ['status'=> false , 'message'=>'File not found.'];
    }

    private function deleteFileAndUpdateArray(&$files, $index)
    {
        $this->unlinkFiles($files[$index]);
        unset($files[$index]);
    }

    public function removeApplicants($ids)
    {
        $this->model->destroy($ids);
    }

    public function updateBulkApplicants(array $data, $applicants)
    {
        foreach ($applicants as $applicant) {
            $applicant->deposit_amount = $data['deposit_amount'] ?? $applicant->deposit_amount;
            $applicant->loi_payment_date = $data['loi_payment_date'] ?? $applicant->loi_payment_date;
            $applicant->loi_payment_receipt_no = $data['loi_payment_receipt_no'] ?? $applicant->loi_payment_receipt_no;
            $applicant->remarks = $data['remarks'] ?? $applicant->remarks;

            if (isset($data['status'])) {
                $applicant->status = $data['status'];
            }

            if (isset($data['payment_letter_copies'])) {
                $applicant->payment_letter_copies = $data['payment_letter_copies'];
            }

            $applicant->save();
        }
    }

    public function getPaxIdsByBatchNo($batchNo): array
    {
        return $this->model->where('batch_no', $batchNo)->pluck('pax_id')->toArray();
    }

    public function showPreviousLoi($currentApplicant): ?Model
    {
        return $this->model->select('loi_payment_date', 'batch_no', 'remarks', 'loi_payment_receipt_no', 'deposit_amount', 'payment_letter_copy')
            ->with('loi_application:batch_no,loi_no', 'pax')
            ->where([
                ['batch_no', '<', $currentApplicant->batch_no],
                ['pax_id', $currentApplicant->pax_id]
            ])->where(function ($query) {
                $query->whereNull('status')->orWhereNotIn('status', [2, 3]);
            })->orderBy('created_at', 'desc')->first();
    }

    public function getBatchNoFromApplicants(array $ids)
    {
        return $this->model->whereIn('id', $ids)->value('batch_no');
    }

    public function getExportData($batches)
    {
        $batches = Arr::wrap($batches);
        return $this->model->whereIn('batch_no', $batches)->orderBy('batch_no')->orderBy('sequence_no')->get();
    }

    public function getLastApplicantByPaxId($pax_id)
    {
        $last_applicant =  $this->model->where('pax_id', $pax_id)
        ->orderBy('created_at', 'desc')
        ->first();
        $loi_payment_date = null;
        $loi_payment_receipt_no = null;
        $deposit_amount = null;
        $payment_letter_copy = null;
        $loi_photo_copy = null;
        $loi_issue_date = null;
        $loi_no = null;
        if($last_applicant){
            $loi = $last_applicant->loi_application;
            if($last_applicant->loi_no  == null && $last_applicant->loi_issue_date == null && $last_applicant->loi_photo_copy == null){
                $loi_photo_copy = $loi->loi_photo_copy;
                $loi_issue_date = $loi->loi_issue_date;
                $loi_no = $loi->loi_no;
            }else{
                $loi_photo_copy = $last_applicant->loi_photo_copy;
                $loi_issue_date = $last_applicant->loi_issue_date;
                $loi_no = $last_applicant->loi_no;
            }
            if($last_applicant->loi_payment_date != null ||$last_applicant->loi_payment_receipt_no != null ||  $last_applicant->deposit_amount != null || $last_applicant->payment_letter_copy != []){
                if($last_applicant->status != 2 || $last_applicant->status != 3){
                    $loi_payment_date = $last_applicant->loi_payment_date;
                    $loi_payment_receipt_no = $last_applicant->loi_payment_receipt_no;
                    $deposit_amount = $last_applicant->deposit_amount;
                    $payment_letter_copy = $last_applicant->payment_letter_copy != [] ? implode(',', $last_applicant->payment_letter_copy) : '';
                }
            }else{
                $loi_payment_date = $loi->moi_payment_date;
                $loi_payment_receipt_no = $loi->moi_invoice;
                $deposit_amount = $loi->moi_deposit;
                $payment_letter_copy = $loi->payment_copy;
            }
        }
        return [
            'loi_payment_date' => $loi_payment_date,
            'deposit_amount' => $deposit_amount,
            'loi_payment_receipt_no' => $loi_payment_receipt_no,
            'payment_letter_copy' => $payment_letter_copy,
            'loi_photo_copy' => $loi_photo_copy,
            'loi_no' => $loi_no,
            'loi_issue_date' => $loi_issue_date,
        ];
    }
}
