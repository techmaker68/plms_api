<?php
// ***********************************
// @author Syed, Umair, Aqsa, Saqib, Majid
// @create_date 21-07-2023
// ***********************************
namespace Modules\Loi\Entities;

use App\Models\Country;
use App\Traits\HandleFiles;
use Modules\Pax\Entities\PLMSPax;
use Modules\Visa\Entities\PLMSVisa;
use Illuminate\Database\Eloquent\Model;
use Modules\Passport\Entities\PLMSPassport;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PLMSLoiApplicant extends Model
{
    use Hasfactory ,LogsActivity, HandleFiles;

    protected $table = 'plms_loi_applicants';
    protected static $logAttributes = ["*"];
    protected static $logOnlyDirty = true;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pax_id', 
        'batch_no', 
        'status', 
        'loi_payment_date',
        'deposit_amount',
        'loi_payment_receipt_no',
        'remarks',
        'full_name',
        'arab_full_name',
        'passport_no',
        'nationality',
        'department',
        'employer',
        'pax_type',
        'email',
        'sequence_no',
        'position',
        'arab_position',
        'country_residence',
        'phone',
        'payment_letter_copy',
        'loi_photo_copy',
        'loi_no',
        'loi_issue_date',
    ];

    public function setPaymentLetterCopyAttribute($value)
    {
        $this->handleFileUpload($value, 'payment_letter_copy', 'loiApplicants/images');
    }

    public function loi_application()
    {
        return $this->belongsTo(PLMSLoi::class,'batch_no','batch_no');
    }

    /*
    * relation with pax 
    */
    public function pax()
    {
        return $this->belongsTo(PLMSPax::class , 'pax_id', 'pax_id');
    }

    public function countryOfNationality()
    {
        return $this->hasOne(Country::class , 'id', 'nationality');
    }

    public function countryOfPassport()
    {
        return $this->hasOne(Country::class, 'id', 'passport_country');
    }

    public function countryResidenceLoi()
    {
        return $this->hasOne(Country::class, 'id', 'country_residence');
    }

    public function getCountryResidenceLoiArabicName()
    {
        return $this->countryResidence->country_name_short_ar ?? null;
    }
    public function getCountryOfNationalityEngName()
    {
        return $this->countryOfNationality->nationality_en ?? null;
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

    public function generateRemarks()
    {
        if ($this->loi_payment_receipt_no !== null) {
            return 'تم دفع التأمينات حسب الوصل المرقم' 
                . '(' . $this->loi_payment_receipt_no . ')'
                . '(ص)(بتأريخ ' . $this->loi_payment_date . ')';
        }

        return 'غير مؤمن';
    }

    public function getPaymentLetterCopyAttribute($value)
    {
        return convertFileToArray($value);
    }

    public function storeImage($image)
    {
        $imageName = uniqid().'-'.time().".".$image->getClientOriginalExtension();
        $destination = 'media/loiApplicants/images/';
        $path= $image->move($destination,$imageName);
        $finalPath = substr($path, strlen('media/'));
        return  $finalPath;
    }

    public static function setSequenceNumberForAll()
    {
        $allBatchNos = self::distinct('batch_no')->pluck('batch_no');

        foreach ($allBatchNos as $batch_no) {
        
            // 1. Fetch the records for the given batch_no which have sequence_no as null or 0
            $applicantsToUpdate = self::where('batch_no', $batch_no)
                ->where(function($query){
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

    public function getPaxIdAttribute($value)
    {
        return convertPaxId($value);
    }

    public function getBatchNoAttribute($value)
    {
        return str_pad($value, 4, '0', STR_PAD_LEFT);
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('pax_id', $this->getPaxIdAttribute($searchTerm))  
        ->orWhere('full_name', 'LIKE', "%{$searchTerm}%") 
        ->orWhere('badge_no', 'LIKE', "%{$searchTerm}%") 
        ->orWhere('batch_no', $this->getBatchNoAttribute($searchTerm)) 
        ->orWhere('passport_no', 'LIKE', "%{$searchTerm}%");
    }
    public function getLoiStaatus()
    {
        if($this->status!=null) {
       if($this->status==0){
        return 'approved';
       }else if ($this->status==1) {
        return 'rejected';

       }else if ($this->status==2) {
        return 'cancelled';

       }else if($this->status==3){
        return 'give up';
       }
    }

    }

    public function lastLoiApplicantByPax($batch_no, $pax_id)
    {
       return $this->select('loi_payment_date', 'batch_no','loi_payment_receipt_no')
            ->where([
                ['batch_no', '<', $batch_no],
                ['pax_id', $pax_id]
            ])->where(function ($query) {
                $query->whereNull('status')->orWhereNotIn('status', [2, 3]);
            })->orderBy('created_at', 'desc')->first();
    }
	
}