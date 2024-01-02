<?php

namespace Modules\BloodTest\Repositories;

use App\Mail\PenaltyEmail;
use Illuminate\Support\Arr;
use App\Mail\NewAppointment;
use App\Mail\SendApplicantsNote;
use Modules\Pax\Entities\PLMSPax;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Builder;
use Modules\BloodTest\Entities\BloodTest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\BloodTest\Entities\PLMSBloodApplicant;
use Modules\BloodTest\Contracts\BloodApplicantRepositoryContract;

class BloodApplicantRepository extends BaseRepository implements BloodApplicantRepositoryContract
{
    /**
     * BloodApplicantRepository constructor.
     *
     * @param PLMSBloodApplicant $model The Eloquent model.
     */
    protected $adminEmail;
    public function __construct(PLMSBloodApplicant $model)
    {
        parent::__construct($model);
        $this->adminEmail = config('mail.blood_admin');
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
            case 'search':
                $query->search($value);
                break;
            case 'batch_no':
                $query->where('batch_no', $value);
                break;
            case 'sort_by':
                $order = $allFilters['order'] ?? 'asc';
                $sortBy = $value ?? 'sequence_no';
                $query->orderBy($sortBy, $order);
                break;
        }
    }

    public function getApplicantsByIds($ids, array $relations = []): Collection
    {
        $ids = Arr::wrap($ids);
        return $this->model->with($relations)->whereIn('id', $ids)->get();
    }
    
    public function updateApplicantsInBulk(int $id, array $updatedData)
    {
        $applicant = $this->model->findOrFail($id);
        $applicant->update($updatedData);
    }
    
    public function setPaxStatus($pax, $status): void
    {
        $pax->status = $status;
        $pax->save();
    }

    public function getPaxDetail($pax_id)
    {
        $result['blood_test_suggestions'] = [];

        $pax = PLMSPax::with(['company', 'passports', 'department'])->where('pax_id', $pax_id)->first();
        $specific_countries = config('BloodTest.specific_countries');
        if ($pax->nationality != null) {
            if (in_array($pax->country->country_code_2, $specific_countries)) {
                $result['blood_test_suggestions'][] = ['label' => 'HIV', 'default' => true];
            } else {
                $result['blood_test_suggestions'][] = ['label' => 'HIV', 'default' => false];
            }
        }
        $bloodTestsLastYear = $pax->blood_tests()
            ->whereYear('plms_blood_tests.created_at', date("Y") - 1)
            ->get();

        if ($bloodTestsLastYear->isEmpty()) {
            $result['blood_test_suggestions'][] = ['label' => 'HBS', 'default' => true];
        } else {
            $result['blood_test_suggestions'][] = ['label' => 'HBS', 'default' => false];
        }
        return [
            'pax' => $pax,
            'result' => $result,
        ];
    }

    public function applicantVisaTaskPurposeStatus($data)
    {
        $pax = PLMSPax::where('pax_id', $data['pax_id'])->first();
        if ($pax) {
            $taskPurposes = $data['task_purposes'];
            if ($taskPurposes) {
                if (
                    isset($taskPurposes['visa']) &&
                    $taskPurposes['visa']['status'] === true &&
                    $taskPurposes['visa']['values'] === 'Offboard'
                ) {
                    $paxStatus = 2;
                    $pax->status = $paxStatus;
                    $pax->save();
                }
            }
        }
    }
    
    public function getBatchNoFromApplicants(array $ids)
    {
        return $this->model->whereIn('id', $ids)->value('batch_no');
    }

    public function getBatchByNo($batch_no)
    {
        return BloodTest::where('batch_no', $batch_no)->first();
    }

    public function getMaxSequenceNoForBatch($batch_no): int
    {
        return $this->model->where('batch_no', $batch_no)->max('sequence_no') ?: 0;
    }

    public function rescheduleApplicant($data)
    {
        $applicants = $data['applicants'];
        $batch = $data['batch'];
        $sequence_no = $data['sequence_no'];

        $applicants->each(function ($applicant) use ($sequence_no) {
            $applicant->scheduled_status = $applicant->scheduled_status ? 0 : 1;
            $applicant->appoint_time = $applicant->scheduled_status ? $applicant->appoint_time : null;
            $applicant->sequence_no = $applicant->scheduled_status == '1' ?  $sequence_no : $applicant->sequence_no ;
            $applicant->save();
        });

        $batch->processBatch($batch->batch_no);

        return $applicants;
    }

    public function saveApplicant($applicant)
    {
        $applicant->save();
    }

    public function submissionInformation($batch_no)
    {
        $application = $this->getBatchByNo($batch_no);
        $year = date('y'); // get last 2 digits of year
        $week = date('W'); // get week number
        // Combine year and week to your desired format
        $current_batch_no = $year . $week;
        $enabled = $current_batch_no == $batch_no ? true : false;
        return [
            'id' => $application->id,
            'batch_no' => $application->batch_no,
            'submit_date' => $application->submit_date,
            'test_date' => $application->test_date,
            'return_date' => $application->return_date,
            'venue' => $application->venue_name,
            'applicants_interval' => $application->applicants_interval,
            'start_time' => $application->start_time,
            'end_time' => $application->end_time,
            'interval' => $application->interval,
            'submitted' => $application->submitted(),
            'tested' => $application->tested(),
            'returned' => $application->returned(),
            'enabled' => $enabled,
        ];  
    }


    public function removeApplicants($ids)
    {
        $this->model->destroy($ids);
    }

    public function getLastBloodTest($bloodTest)
    {
        $pax = $applicant->pax ?? null;
        $penalty = [];
        $bloodHistory = [];
        $last_blood_tests = $pax ? $pax->blood_tests()->orderBy('id', 'desc')->skip(1)->take(PHP_INT_MAX)->get() : null;
        if($last_blood_tests != null){
            foreach($last_blood_tests as $last_blood_test){
                $old_blood_applicants = $last_blood_test->blood_applicant()->where('pax_id', $pax->pax_id)->get() ?? null;
                if($old_blood_applicants != null){
                    foreach($old_blood_applicants as $old_applicant){
                    if($old_applicant->penalty_fee != null){
                        $penalty[] = array(
                            'appoint_date' => $last_blood_test->test_date,
                            'penalty_fee' => $old_applicant->penalty_fee,
                            'penalty_remarks' => $old_applicant->penalty_remarks,
                        );
                    }
                    }
                }
                $bloodHistory[] =  array(
                    'id' => $last_blood_test->id,
                    'submit_date' => $last_blood_test->submit_date,
                    'test_date' => $last_blood_test->test_date,
                    'return_date' => $last_blood_test->return_date,
                    'venue' => $last_blood_test->venue_name,
                    'appoint_time' => $last_blood_test->start_time,

                );
            }
        }
        return [
            'penalty'=> $penalty,
            'blood_test_history' => $bloodHistory
        ];
    }
}
