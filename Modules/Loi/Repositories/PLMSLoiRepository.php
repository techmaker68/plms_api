<?php

namespace Modules\Loi\Repositories;

use App\Repositories\BaseRepository;
use App\Traits\HandleFiles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Loi\Contracts\PLMSLoiRepositoryContract;
use Modules\Loi\Entities\PLMSLoi;

class PLMSLoiRepository extends BaseRepository implements PLMSLoiRepositoryContract
{
    use HandleFiles;
    /**
     * PLMSLoiRepository constructor.
     *
     * @param PLMSLoi $model The Eloquent model.
     */
    public function __construct(PLMSLoi $model)
    {
        parent::__construct($model);
    }

    public function all(array $filters, ?array $withRelations = []): Collection
    {
        return $this->applyQueryFilters($this->getQueryInstance($withRelations), $filters)->get();
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
            case 'search_by_applicant':
                $query->searchByApplicant($value);
                break;

            case 'search':
                $query->search($value);
                break;

            case 'status':
                $query->status($value);
                break;

            case 'company_id':
                $query->companyId($value);
                break;

            case 'sort_by':
                $orderBy = $allFilters['order'] ?? 'asc';
                $query->sortBy($value, $orderBy);
                break;

            case 'priority':
                $query->priority($value);
                break;
            case 'issued':
                $query->issued($value);
                break;
        }
    }

    public function getMaxBatchNo(): int
    {
        return $this->model->max('batch_no') ?: 0;
    }

    public function getOldBatchRecord(int $oldBatchNo): ?Model
    {
        return $this->model->where('batch_no', $oldBatchNo)->first();
    }

    public function createNewBatchFromOld($oldLOI, int $newBatchNo): Model
    {
        $newLOI = $this->model->create([
            'batch_no' => $newBatchNo,
            'nation_category' => $oldLOI->nation_category,
            'loi_type' => $oldLOI->loi_type,
            'company_id' => $oldLOI->company_id,
        ]);

        return $newLOI;
    }

    public function getLoiByBatchNo(int $batchNo): Model
    {
        return $this->model->where('batch_no', $batchNo)->first();
    }

    public function getDefaultDataForLoiStorage(): array
    {

        $prev_record = $this->model->orderBy('batch_no', 'desc')->orderBy('created_at', 'desc')->first();

        return [
            'submission_date' => date('Y-m-d H:i:s'),
            'company_id' => $prev_record->company_id ?? 1,
            'company_address_iraq_ar' => $prev_record->company_address_iraq_ar ?? "بغداد\\الجادرية\\محلة 913\\زقاق 34\\دار 85",
            'entry_purpose' => $prev_record->entry_purpose ?? "العمل في تطوير حقل مجنون",
            'entry_type' => $prev_record->entry_type ?? "متعددة الدخول",
            'contract_expiry' => $prev_record->contract_expiry ?? null,
            'company_address_ar' => $prev_record->company_address_ar ?? "Unit No.1901,Swiss Tower,JLT,Dubai,UAE",
        ];
    }

    public function deleteFilesAndUpdateRecord($loi, $indexes)
    {
        foreach ($indexes as $key => $index) {
            $fileAttribute = $this->getFileAttributeFromKey($key);
            $files = $this->getFilesArray($loi->{$fileAttribute});

            if (isset($files[$index]) && $files[$index] !== "") {
                $this->deleteFileAndUpdateArray($files, $index);
                $loi->{$fileAttribute} = implode(',', $files);
            }
        }

        $loi->save();
    }

    private function deleteFileAndUpdateArray(&$files, $index)
    {
        $this->unlinkFiles($files[$index]);
        unset($files[$index]);
    }

    private function getFileAttributeFromKey($key)
    {
        switch ($key) {
            case 'boc_moo_index':return 'boc_moo_copy';
            case 'mfd_index':return 'mfd_copy';
            case 'hq_index':return 'hq_copy';
            case 'payment_copy_index':return 'payment_copy';
            case 'loi_photo_copy_index':return 'loi_photo_copy';
            default:return null;
        }
    }

    public function deleteLoiWithApplicants($loi)
    {
        if (!$loi) {
            return false;
        }

        $loi->applicants->each(function ($applicant) {
            $this->unlinkFiles($applicant->payment_letter_copy);
            $this->unlinkFiles($applicant->loi_photo_copy);
        });

        $this->unlinkFiles($loi->hq_copy);
        $this->unlinkFiles($loi->boc_moo_copy);
        $this->unlinkFiles($loi->mfd_copy);
        $this->unlinkFiles($loi->loi_photo_copy);
        $this->unlinkFiles($loi->payment_copy);

        $loi->applicants()->delete();
        $loi->delete();

        return true;
    }

    public function getLoiDetails($batchNo)
    {
        return PLMSLoi::where('batch_no', $batchNo)
        ->with(['applicants' => function ($query) {
            $query->orderBy('sequence_no');
        }, 'company', 'applicants.pax'])
        ->first();
    }
}
