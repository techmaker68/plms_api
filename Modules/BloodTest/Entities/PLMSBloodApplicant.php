<?php

namespace Modules\BloodTest\Entities;

use App\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Company\Entities\PLMSCompany;
use Modules\Department\Entities\PLMSDepartment;
use Modules\Passport\Entities\PLMSPassport;
use Modules\Pax\Entities\PLMSPax;
use Modules\Visa\Entities\PLMSVisa;
use Spatie\Activitylog\Traits\LogsActivity;

class PLMSBloodApplicant extends Model
{
    use Hasfactory, LogsActivity;
    protected $table = 'plms_blood_applicants';
    protected static $logAttributes = ["*"];
    protected static $logOnlyDirty = true;
    protected $guarded = ['id', 'created_at', 'updated_at'];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pax_id',
        'batch_no',
        'arrival_date',
        'departure_date',
        'passport_return_date',
        'passport_submit_date',
        'hbs_expire_date',
        'hiv_expire_date',
        'appoint_date',
        'appoint_time',
        'attendance',
        'blood_test_types',
        'remarks',
        'passport_no',
        'full_name',
        'passport_country',
        'birthday',
        'passport_issue_date',
        'passport_expiry_date',
        'employer',
        'badge_no',
        'position',
        'department',
        'email',
        'phone',
        'nationality',
        'scheduled_status',
        'sequence_no',
        'new_appoint_date',
        'new_remarks',
        'penalty_remarks',
        'visa_penalty_fee',
        'visa_penalty_remarks',
        'penalty_fee',
        'task_purposes',
        'country_code_id',
    ];

    protected $casts = [
        'task_purposes' => 'array',
    ];

    /*
    * get paxes
    */
    public function blood_test()
    {
        return $this->belongsTo(BloodTest::class, 'batch_no', 'batch_no');
    }

    /*
        * relation with pax 
        */
    public function pax()
    {
        return $this->belongsTo(PLMSPax::class, 'pax_id', 'pax_id');
    }

    public function latestPassport()
    {
        return $this->hasOne(PLMSPassport::class, 'pax_id', 'pax_id')
            ->latest('created_at');
    }

    public function latestVisa()
    {
        return $this->hasOne(PLMSVisa::class, 'pax_id', 'pax_id')
            ->latest('created_at');
    }
    
    public function countryCode()
    {
        return $this->hasOne(Country::class, 'id', 'country_code_id');
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'nationality');
    }

    public function passportCountry()
    {
        return $this->hasOne(Country::class, 'id', 'passport_country');
    }

    public function getCountryEngName()
    {
        return $this->country->nationality_en ?? null;
    }

    public function getPassportCountryEngName()
    {
        return $this->passportCountry->country_name_short_en ?? null;
    }

    public function company()
    {
        return $this->hasOne(PLMSCompany::class,  'id', 'employer');
    }

    public function applicant_department()
    {
        return $this->hasOne(PLMSDepartment::class,  'id', 'department');
    }

    public function getDepartmentName()
    {
        return $this->applicant_department->name ?? null;
    }

    public function getDepartmentId()
    {
        return $this->applicant_department->id ?? null;
    }

    public function getCompanyName()
    {
        return $this->company->name ?? null;
    }

    public function getPaxIdAttribute($value)
    {
        return convertPaxId($value);
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('pax_id', $this->getPaxIdAttribute($searchTerm))
            ->orWhere('full_name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('badge_no', 'LIKE', "%{$searchTerm}%")
            ->orWhere('batch_no', 'LIKE', "%{$searchTerm}%")
            ->orWhere('passport_no', 'LIKE', "%{$searchTerm}%")
            ->orWhere('employer', 'LIKE', "%{$searchTerm}%")
            ->orWhere('scheduled_status', 'LIKE', "%{$searchTerm}%")
            ->orWhere('arrival_date', 'LIKE', "%{$searchTerm}%")
            ->orWhere('departure_date', 'LIKE', "%{$searchTerm}%")
            ->orWhere('appoint_time', 'LIKE', "%{$searchTerm}%")
            ->orWhereHas('pax', function ($paxQuery) use ($searchTerm) {
                $paxQuery->search($searchTerm);
            });
    }

    public static function setSequenceNumberForAll()
    {
        $allBatchNos = self::distinct('batch_no')->pluck('batch_no');

        foreach ($allBatchNos as $batch_no) {

            // 1. Fetch the records for the given batch_no which have sequence_no as null or 0
            $applicantsToUpdate = self::where('batch_no', $batch_no)
                ->where(function ($query) {
                    $query->whereNull('sequence_no')->orWhere('sequence_no', 0);
                })
                ->orderBy('id') // or any other column you'd want to sort by
                ->get();

            // 2. Get the maximum sequence_no for that batch_no
            $maxSeq = self::where('batch_no', $batch_no)->max('sequence_no');

            // If there's no sequence number yet for this batch, start from 1
            $nextSeq = $maxSeq ? $maxSeq + 1 : 1;
            // 3. Update the fetched records with an increasing sequence_no from the maximum value
            foreach ($applicantsToUpdate as $applicant) {
                $applicant->sequence_no = $nextSeq;
                $applicant->save();
                $nextSeq++;
            }
        }
    }
}
