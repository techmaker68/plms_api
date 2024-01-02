<?php
// ***********************************
// @author Syed, Umair, Aqsa, Saqib, Majid
// @create_date 21-07-2023
// ***********************************
namespace Modules\Visa\Entities;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Passport\Entities\PLMSPassport;
use Modules\Pax\Entities\PLMSPax;
use Modules\Loi\Entities\PLMSLoi;
use Modules\Loi\Entities\PLMSLoiApplicant;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\HandleFiles;

class PLMSVisa extends Model
{
    use HasFactory, LogsActivity, HandleFiles;

    protected $table = 'plms_visas';

    protected static $logAttributes = ["*"];
    protected static $logOnlyDirty = true;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'pax_id',
        'loi_id',
        'passport_id',
        'type',
        'visa_no',
        'date_of_issue',
        'date_of_expiry',
        'file',
        'status',
        'reason',
        'visa_location',
    ];

    public function setFileAttribute($value)
    {
        $this->handleFileUpload($value, 'file', 'visas/images');
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

    public function passport()
    {
        return $this->hasOne(PLMSPassport::class, 'id', 'passport_id');
    }

    public function loi()
    {
        return $this->hasOne(PLMSLoi::class, 'id', 'loi_id');
    }

    /**
     * store image process
     *
     * @return 
     */
    public function storeImage($image)
    {
        $imageName = uniqid() . '-' . time() . "." . $image->getClientOriginalExtension();
        $destination = 'media/visas/images/';
        $path = $image->move($destination, $imageName);
        $finalPath = substr($path, strlen('media/'));
        return  $finalPath;
    }

    public function getPaxIdAttribute($value)
    {
        return convertPaxId($value);
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('pax_id', $this->getPaxIdAttribute($searchTerm))
            ->orWhere('date_of_issue', 'LIKE', "%{$searchTerm}%")
            ->orWhere('date_of_expiry', 'LIKE', "%{$searchTerm}%")
            ->orWhereHas('pax', function ($paxQuery) use ($searchTerm) {
                $paxQuery->search($searchTerm);
            })
            ->orWhereHas('pax.passports', function ($passportQuery) use ($searchTerm) {
                $passportQuery->search($searchTerm);
            });
    }

    public function scopeVisaExpiry(Builder $query, $startDate, $endDate = null): Builder
    {
        if ($endDate) {
            $query->whereBetween('date_of_expiry', [$startDate, $endDate]);
        } else {
            $query->where('date_of_expiry', '>=', $startDate);
        }
        return $query;
    }

    public function historicalVisa() : Collection
    {
        return  $this->pax->visas
            ->where('created_at', '<', $this->created_at);
    }

    public function checkVisaStatusByDays($expiryDate)
    {
        if($this->status == 5){
            return $this->status;
        }
        if ($expiryDate === NULL) {
            $expiryDate = date('Y-m-d');
        }
        $days = daysPassedAndRemaining($expiryDate);
        if ($days['remaining'] <= 0) {
            $status = 2;
        } else if ($days['remaining'] <= 30) {
            $status = 3;
        } else if ($days['remaining'] <= 90) {
            $status = 4;
        } else {
            $status = 1;
        }
        return $status;
    }

    public function getVisaStatusString(?int $statusId): string
    {
        if ($statusId == 1) {
            return "Approved";
        } else if ($statusId == 2) {
            return "Expired";
        } else if ($statusId == 3 || $statusId == 4) {
            return "TO be Renewed";
        }else{
            return "Cancelled";
        }
        return '';
    }
}
