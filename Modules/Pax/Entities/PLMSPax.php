<?php
// ***********************************
// @author Syed, Umair, Aqsa, Saqib, Majid
// @create_date 21-07-2023
// ***********************************
namespace Modules\Pax\Entities;

use App\Models\Country;
use Modules\Loi\Entities\PLMSLoi;
use App\Traits\HandleFiles;
use Modules\BloodTest\Entities\PLMSBloodApplicant;
use Modules\Visa\Entities\PLMSVisa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\BloodTest\Entities\BloodTest;
use Modules\Company\Entities\PLMSCompany;
use Modules\Passport\Entities\PLMSPassport;
use Spatie\Activitylog\Traits\LogsActivity;
use Modules\Department\Entities\PLMSDepartment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Loi\Entities\PLMSLoiApplicant;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;


class PLMSPax extends Model
{
    use HasFactory, LogsActivity, HandleFiles;
    protected $table = 'plms_paxes';
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
        'type',
        'pax_id',
        'company_id',
        'department_id',
        'first_name',
        'last_name',
        'eng_full_name',
        'arab_full_name',
        'nationality',
        'position',
        'phone',
        'email',
        'gender',
        'badge_no',
        'status',
        'file',
        'dob',
        'arab_position',
        'country_residence',
        'offboard_date',
        'country_code_id',
    ];

    public function setFileAttribute($value)
    {
        $this->handleFileUpload($value, 'file', 'paxes/images');
    }

    /*
     * relation with pax
     */
    public function company()
    {
        return $this->belongsTo(PLMSCompany::class, 'company_id');
    }

    /*
     * relation with pax
     */
    public function department()
    {
        return $this->belongsTo(PLMSDepartment::class, 'department_id');
    }

    /*
     * relation with passport
     */
    public function passports()
    {
        return $this->hasMany(PLMSPassport::class, 'pax_id', 'pax_id');
    }

    /*
     * relation with blood applicants
     */
    public function blood_applicants()
    {
        return $this->hasMany(PLMSBloodApplicant::class, 'pax_id', 'pax_id');
    }

    /*
     * relation with blood applicants
     */
    public function loi_applicants()
    {
        return $this->hasMany(PLMSLoiApplicant::class, 'pax_id', 'pax_id');
    }

    public function visas()
    {
        return $this->hasMany(PLMSVisa::class, 'pax_id', 'pax_id');
    }

    public function blood_tests()
    {
        return $this->hasManyThrough(
            BloodTest::class,
            PLMSBloodApplicant::class,
            'pax_id', // Foreign key on PLMSBloodApplicant table...
            'batch_no', // Foreign key on PLMSBloodTest table...
            'pax_id', // Local key on PLMSPax table...
            'batch_no' // Local key on PLMSBloodApplicant table...
        );
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'nationality');
    }

    public function countryCode()
    {
        return $this->hasOne(Country::class, 'id', 'country_code_id');
    }

    public function countryResidence()
    {
        return $this->hasOne(Country::class, 'id', 'country_residence');
    }

    public function lois()
    {
        return $this->hasManyThrough(
            PLMSLoi::class,
            PLMSLoiApplicant::class,
            'pax_id', // Foreign key on PLMSLOIApplicant table...
            'batch_no', // Foreign key on PLMSLOI table...
            'pax_id', // Local key on PLMSPax table...
            'batch_no' // Local key on PLMSLOIApplicant table...
        );
    }

    public function latestLoi()
    {
        return $this->hasOneThrough(
            PLMSLoi::class,
            PLMSLoiApplicant::class,
            'pax_id',
            'batch_no',
            'pax_id',
            'batch_no'
        )->orderByDesc('plms_lois.created_at')->limit(1);
    }

    public function getLatestLoiAttribute()
    {
        return $this->latestLoi()->first();
    }

    public function latestVisa()
    {
        return $this->hasOne(PLMSVisa::class, 'pax_id', 'pax_id')->latest();
    }

    public function latestBloodTest()
    {
        return $this->hasOneThrough(
            BloodTest::class,
            PLMSBloodApplicant::class,
            'pax_id',
            'batch_no',
            'pax_id',
            'batch_no'
        )->latest();
    }

    public function latestPassport()
    {
        return $this->hasOne(PLMSPassport::class, 'pax_id', 'pax_id')->with('countryOfPassport','countryOfIssue')->latest();
    }

    public function getPaxIdAttribute($value)
    {
        return convertPaxId($value);
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('pax_id', $this->getPaxIdAttribute($searchTerm))
            ->orWhere('eng_full_name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('badge_no', 'LIKE', "%{$searchTerm}%")
            ->orWhere('arab_full_name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('first_name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
            ->orWhereHas('passports', function ($innerQuery) use ($searchTerm) {
                $innerQuery->where('passport_no', 'LIKE', "%{$searchTerm}%");
            });
    }

    public function scopePosition(Builder $query, $value): Builder
    {
        return $query->where(function ($q) use ($value) {
            $q->where('position', 'like', '%' . $value . '%')
                ->orWhere('arab_position', 'like', '%' . $value . '%');
        });
    }

    public function scopeWithoutPassport(Builder $query): Builder
    {
        return $query->doesntHave('passports');
    }

    public function scopeWithoutVisa(Builder $query): Builder
    {
        return $query->doesntHave('visas');
    }

    public function scopeWithoutBadge(Builder $query): Builder
    {
        return $query->whereNull('badge_no');
    }

    public function scopeSimilarName(Builder $query): Builder
    {
        return $query->whereIn('eng_full_name', function ($subQuery) {
            $subQuery->select('eng_full_name')
                ->from('plms_paxes')
                ->groupBy('eng_full_name')
                ->havingRaw('COUNT(*) > 1');
        });
    }

    public function scopeVisaExpiry(Builder $query, $startDate, $endDate = null): Builder
    {
        return $query->whereHas('visas', function ($q) use ($startDate, $endDate) {
            if ($endDate) {
                $q->whereBetween('date_of_expiry', [$startDate, $endDate]);
            } else {
                $q->where('date_of_expiry', '>=', $startDate);
            }
        });
    }

    public function scopeTypes(Builder $query, $value): Builder
    {
        $value = Arr::wrap($value);
        return $query->when(in_array('Unassigned', $value), function ($q) use ($value) {
            $q->whereIn('type', $value)->orWhereNull('type');
        }, function ($q) use ($value) {
            $q->whereIn('type', $value);
        });
    }

    public function scopeNationCategory(Builder $query, $categories)
    {
        return $query->where(function ($q) use ($categories) {
            foreach ((array) $categories as $category) {
                $this->applyNationCategoryFilter($q, $category);
            }
        });
    }

    protected function applyNationCategoryFilter(Builder $query, string $category)
    {
        switch ($category) {
            case 'syrian':
                $query->orWhereHas('country', function ($subQuery) {
                    $subQuery->where('country_code_2', config('pax.syrian'));
                });
                break;
            case 'arab':
                $query->orWhereHas('country', function ($subQuery) {
                    $subQuery->whereIn('country_code_2', config('pax.arab'));
                });
                break;
            default:
                $syrianCode = config('pax.syrian');
                $arabCodes = config('pax.arab');
                $excludedCountries = array_merge([$syrianCode], $arabCodes);
                $query->orWhere(function ($q) use ($excludedCountries) {
                    $q->whereHas('country', function ($subQuery) use ($excludedCountries) {
                        $subQuery->whereNotIn('country_code_2', $excludedCountries);
                    })->orWhereDoesntHave('country');
                });
                break;
        }
    }    

    // This will return the paxes which are not assigned to the given route
    public function scopeRouteFilter(Builder $query, string $route, $batch_no = null)
    {
        switch ($route) {
            case 'blood_test':
                return $query->whereDoesntHave('blood_tests', function ($q) use ($batch_no) {
                    $q->where('plms_blood_tests.batch_no', $batch_no);
                });

            case 'loi_request':
                return $query->whereDoesntHave('lois', function ($q) use ($batch_no) {
                    $q->where('plms_lois.batch_no', $batch_no);
                });

            default:
                return $query;
        }
    }

    protected function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d');
    }

    public function getBloodTestDetails()
{
    return $this->blood_tests()
        ->select([
            'plms_blood_applicants.arrival_date',
            'plms_blood_applicants.departure_date',
            'plms_blood_tests.batch_no',
            'plms_blood_tests.test_date',
            'plms_blood_tests.return_date',
            'plms_blood_tests.submit_date',
            'plms_blood_tests.venue',
            DB::raw('(CASE WHEN plms_blood_applicants.scheduled_status = 1 THEN "Scheduled" ELSE "Unscheduled" END) as scheduled_status'),
            DB::raw('(CASE WHEN plms_blood_tests.venue = 1 THEN "J Block" WHEN plms_blood_tests.venue = 2 THEN "DB1 Clinic" WHEN plms_blood_tests.venue = 3 THEN "PC Clinic" ELSE plms_blood_tests.venue END) as venue_name'),
            DB::raw('LPAD(plms_blood_applicants.pax_id, 6, "0") AS pax_id'),
            DB::raw('COALESCE(plms_blood_applicants.passport_no, (SELECT passport_no FROM plms_passports WHERE pax_id = plms_blood_applicants.pax_id ORDER BY id DESC LIMIT 1)) AS passport_no'),
            'plms_blood_applicants.appoint_time',
            'plms_blood_applicants.attendance',
            'plms_blood_applicants.passport_submit_date',
            'plms_blood_applicants.passport_return_date',
            'plms_blood_tests.id',
        ])
        ->get();
}


}
