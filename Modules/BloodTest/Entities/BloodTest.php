<?php
// ***********************************
// @author Syed, Umair, Aqsa, Saqib, Majid
// @create_date 21-07-2023
// ***********************************
namespace Modules\BloodTest\Entities;

use Carbon\Carbon;
use Modules\BloodTest\Entities\PLMSBloodApplicant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BloodTest extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'plms_blood_tests';
    protected static $logAttributes = ["*"];
    protected static $logOnlyDirty = true;


    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'batch_no',
        'test_date',
        'return_date',
        'submit_date',
        'venue',
        'start_time',
        'end_time',
        'interval',
        'applicants_interval',
    ];

    /* 
    * get paxes
    */
    public function blood_applicant()
    {
        return $this->hasMany(PLMSBloodApplicant::class, 'batch_no', 'batch_no');
    }

    public function submitted()
    {
        return $this->blood_applicant()->count();
    }

    public function tested()
    {
        return $this->blood_applicant()->where('attendance', 1)->count();
    }

    public function returned()
    {
        return $this->blood_applicant()->whereNotNull('passport_return_date')->count();
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('batch_no', 'LIKE', "%{$searchTerm}%")
            ->orWhereHas('blood_applicant', function ($paxQuery) use ($searchTerm) {
                $paxQuery->search($searchTerm);
            })
            ->orWhereHas('blood_applicant.pax', function ($passportQuery) use ($searchTerm) {
                $passportQuery->search($searchTerm);
            });
    }
    public function scopeFilterByDateRange(Builder $query, $value)
    {
        list($month, $year) = explode('-', $value);
        $date = \DateTime::createFromFormat('m-Y', $value);
        // Calculate the previous month.
        $prevMonthDate = clone $date;
        $prevMonthDate->modify('-1 month');
        $prevMonth = $prevMonthDate->format('m');
        $prevYear = $prevMonthDate->format('Y');
        // Calculate the next month.
        $nextMonthDate = clone $date;
        $nextMonthDate->modify('+1 month');
        $nextMonth = $nextMonthDate->format('m');
        $nextYear = $nextMonthDate->format('Y');

        return $query->where(function ($subQuery) use ($prevMonth, $prevYear, $month, $year, $nextMonth, $nextYear) {
            $subQuery->where(function ($q) use ($prevMonth, $prevYear) {
                $q->whereYear('test_date', '=', $prevYear)
                    ->whereMonth('test_date', '=', $prevMonth);
            })->orWhere(function ($q) use ($month, $year) {
                $q->whereYear('test_date', '=', $year)
                    ->whereMonth('test_date', '=', $month);
            })->orWhere(function ($q) use ($nextMonth, $nextYear) {
                $q->whereYear('test_date', '=', $nextYear)
                    ->whereMonth('test_date', '=', $nextMonth);
            });
        });
    }

    public function getVenueNameAttribute()
    {
        switch ($this->attributes['venue']) {
            case 1:
                return 'J Block';
            case 2:
                return 'DB1 Clinic';
            case 3:
                return 'PC Clinic';
            default:
                return $this->attributes['venue'];
        }
    }

    public function processBatch($batch_no)
    {
        // Retrieve the batch
        $batch = $this->where('batch_no', $batch_no)->first();

        if ($batch) {
            // Retrieve the applicants for the batch
            $applicants = $batch->blood_applicant()->orderBy('sequence_no', 'asc')->get();

            // Fix the sequence numbers
            $this->fixApplicantSequence($applicants);

            // Set the appoint times
            $this->setAppointTimes($batch, $applicants);
        }
    }

    public function fixApplicantSequence($applicants){
        $expected_sequence_no = 1;
    
        $applicants->each(function ($applicant) use (&$expected_sequence_no) {
            if($applicant->scheduled_status == '0'){
                if ($applicant->sequence_no != $expected_sequence_no) {
                    $applicant->sequence_no = $expected_sequence_no;
                    $applicant->save();
                }
            }
            $expected_sequence_no++;
        });
    }

    public function setAppointTimes($batch, $applicants)
    {
        $startTime = Carbon::parse($batch->start_time);
        $interval = $batch->interval;
        $applicantsInterval = $batch->applicants_interval;

        $applicantCount = 0;
        $currentAppointTime = $startTime;  // Initialize the first appoint time

        foreach ($applicants as $applicant) {
            if ($applicant->scheduled_status == 1) {
                $applicantCount++;

                // Check if a new appoint time should be set
                if ($applicantCount > $applicantsInterval) {
                    $currentAppointTime->addMinutes($interval);
                    $applicantCount = 1;  // Reset the applicant count
                }

                $applicant->appoint_time = $currentAppointTime->format('H:i');
                $applicant->save();
            }
        }
    }
}
