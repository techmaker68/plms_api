<?php
// ***********************************
// @author Syed, Umair, Aqsa, Saqib, Majid
// @create_date 21-07-2023
// ***********************************
namespace Modules\Loi\Entities;

use App\Models\Country;
use App\Traits\HandleFiles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Modules\Company\Entities\PLMSCompany;
use Modules\Loi\Entities\PLMSLoiApplicant;
use Spatie\Activitylog\Traits\LogsActivity;

class PLMSLoi extends Model
{
    use Hasfactory, LogsActivity, HandleFiles;

    protected $table = 'plms_lois';
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
        'nation_category',
        'batch_no',
        'loi_type',
        'submission_date',
        'company_id',
        'company_address_iraq_ar',
        'entry_purpose',
        'entry_type',
        'contract_expiry',
        'company_address_ar',
        'company_country',
        'mfd_date',
        'mfd_ref',
        'hq_date',
        'hq_ref',
        'moo_date',
        'moo_ref',
        'boc_moo_date',
        'boc_moo_ref',
        'moi_date',
        'moi_ref',
        'moi_2_date',
        'moi_2_ref',
        'majnoon_date',
        'majnoon_ref',
        'payment_copy',
        'boc_moo_copy',
        'hq_copy',
        'mfd_copy',
        'loi_photo_copy',
        'moi_payment_date',
        'moi_invoice',
        'moi_deposit',
        'loi_issue_date',
        'loi_no',
        'sent_loi_date',
        'close_date',
        'mfd_submit_date',
        'mfd_received_date',
        'hq_submit_date',
        'hq_received_date',
        'boc_moo_submit_date',
        'moi_payment_letter_date',
        'moi_payment_letter_ref',
        'expected_issue_date',
        'priority',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->submission_date)) {
                $model->submission_date = date('Y-m-d H:i:s');
            }
        });
    }

    public function setLoiPhotoCopyAttribute($value)
    {
        $this->handleFileUpload($value, 'loi_photo_copy', 'loi/images');
    }

    public function setPaymentCopyAttribute($value)
    {
        $this->handleFileUpload($value, 'payment_copy', 'loi/images');
    }

    public function setMfdCopyAttribute($files)
    {
        $this->handleFileUpload($files, 'mfd_copy', 'loi/images');
    }

    public function setHqCopyAttribute($files)
    {
        $this->handleFileUpload($files, 'hq_copy', 'loi/images');
    }

    public function setBocMooCopyAttribute($files)
    {
        $this->handleFileUpload($files, 'boc_moo_copy', 'loi/images');
    }

    public function applicants()
    {
        return $this->hasMany(PLMSLoiApplicant::class, 'batch_no', 'batch_no');
    }

    public function getBatchNumberAttribute($value)
    {
        return ltrim($value, '0');
    }

    /*
     * relation with pax
     */
    public function company()
    {
        return $this->belongsTo(PLMSCompany::class, 'company_id');
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'company_country');
    }

    public function getCompanyName()
    {
        return $this->company->name ?? null;
    }

    public function getCountryEngName()
    {
        return $this->country->country_name_short_en ?? null;
    }

    // For mfd_copy
    public function getMfdCopyAttribute($value)
    {
        return convertFileToArray($value);
    }

    // For hq_copy
    public function getHqCopyAttribute($value)
    {
        return convertFileToArray($value);
    }

    // For boc_moo_copy
    public function getBocMooCopyAttribute($value)
    {
        return convertFileToArray($value);
    }


    public function getLoiTypeArabic()
    {
        if ($this->loi_type == 1) {
            return '٣ أشهر';
        } else if ($this->loi_type == 2) {
            return '٦ أشهر';
        } else {
            return ' ١٢ أشهر';
        }
    }
    public function getLoiType()
    {
        if ($this->loi_type == 1) {
            return '3 months';
        } else if ($this->loi_type == 2) {
            return '6 months';
        } else {
            return '12 months';
        }
    }
    public function getSmallestIdRecord()
    {
        $applicant = $this->applicants()->orderBy('sequence_no', 'asc')->first();
        return $applicant;
    }

    public function getLargestIdRecord()
    {
        $applicant = $this->applicants()->orderBy('sequence_no', 'desc')->first();
        return $applicant;
    }
    public function convertToArabicNumber()
    {
        $n = $this->applicants->count();
        $arabicNumbers = [
            '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩',
        ];
        return str_replace(range(0, 9), $arabicNumbers, $n);
    }

    public function getBatchNoAttribute($value)
    {
        return str_pad($value, 4, '0', STR_PAD_LEFT);
    }

    public function scopeSearch($query, $searchTerm)
    {
        if (!empty($searchTerm)) {
            return $query->where('batch_no', $this->getBatchNoAttribute($searchTerm))
                ->orWhere('loi_no', 'LIKE', "%{$searchTerm}%")
                ->orWhereHas('applicants', function ($paxQuery) use ($searchTerm) {
                    $paxQuery->search($searchTerm); // Using the search scope from Pax model
                })
                ->orWhereHas('applicants.pax', function ($passportQuery) use ($searchTerm) {
                    $passportQuery->search($searchTerm); // Using the search scope from Passport model
                });
        }
    }

    public function scopeSearchByApplicant(Builder $query, $value)
    {
        if ($value) {
            $query->whereHas('applicants', function ($q) use ($value) {
                $q->search($value);
            });
        }
    }

    public function scopeStatus(Builder $query, $value)
    {
        $statuses = config('loi.statuses', []);

        if (!in_array($value, $statuses, true)) {
            return;
        }
    
        $query->when($value === 2, function ($q) {
            $q->whereNotNull('close_date');
        })->when($value === 1, function ($q) {
            $q->whereNull('close_date');
        });
    }   

    public function scopeCompanyId(Builder $query, $value)
    {
        $query->whereHas('applicants.pax', function ($q) use ($value) {
            is_array($value) ? $q->whereIn('company_id', $value) : $q->where('company_id', $value);
        });
    }

    public function scopeSortBy(Builder $query, $value, $orderBy)
    {
        if ($value == 'applicants_count') {
            $query->withCount('applicants')->orderBy('applicants_count', $orderBy);
        } else {
            $query->orderBy($value, $orderBy);
        }
    }

    public function scopePriority(Builder $query, $value)
    {
        is_array($value) ? $query->whereIn('priority', $value) : $query->where('priority', $value);
    }

    public function scopeIssued(Builder $query, $value)
    {
        if (in_array($value, config('loi.issued_types'))) {
            $value == 2 ? $query->whereNotNull('loi_issue_date') : $query->whereNull('loi_issue_date');
        }
    }
}
