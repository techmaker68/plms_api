<?php
// ***********************************
// @author Syed, Umair, Aqsa, Saqib, Majid
// @create_date 21-07-2023
// ***********************************
namespace Modules\Passport\Entities;

use App\Models\Country;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Pax\Entities\PLMSPax;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Traits\HandleFiles;

class PLMSPassport extends Model
{
    use HasFactory, LogsActivity, HandleFiles;

    protected $table = 'plms_passports';
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
        'pax_id',
        'full_name',
        'arab_full_name',
        'passport_no',
        'type',
        'date_of_issue',
        'date_of_expiry',
        'birthday',
        'place_of_issue',
        'passport_country',
        'status',
        'file',
    ];

    public function setFileAttribute($value)
    {
        $this->handleFileUpload($value, 'file', 'passports/images');
    }
    /*
	 * relation with pax
	 */
    public function pax()
    {
        return $this->belongsTo(PLMSPax::class, 'pax_id', 'pax_id');
    }

    public function countryOfPassport()
    {
        return $this->hasOne(Country::class, 'id', 'passport_country');
    }

    public function countryOfIssue()
    {
        return $this->hasOne(Country::class, 'id', 'place_of_issue');
    }

    /**
     * store image process
     *
     * @return 
     */
    public function storeImage($image)
    {
        $imageName = uniqid() . '-' . time() . "." . $image->getClientOriginalExtension();
        $destination = 'media/passports/images/';
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
        return $query->where('passport_no', 'LIKE', "%{$searchTerm}%")
            ->orWhere('date_of_issue', 'LIKE', "%{$searchTerm}%")
            ->orWhere('date_of_expiry', 'LIKE', "%{$searchTerm}%")
            ->orWhere('pax_id',  $this->getPaxIdAttribute($searchTerm))
            ->orWhere('passport_no', 'LIKE', "%{$searchTerm}%")
            ->orWhereHas('pax', function ($paxQuery) use ($searchTerm) {
                $paxQuery->search($searchTerm);
            });
    }
    public function scopePassportExpiry(Builder $query, $startDate, $endDate = null): Builder
    {
        if ($endDate) {
            $query->whereBetween('date_of_expiry', [$startDate, $endDate]);
        } else {
            $query->where('date_of_expiry', '>=', $startDate);
        }
        return $query;
    }

    public function getPassportCountryEngName()
    {
        return $this->countryOfPassport->country_name_short_en ?? null;
    }

    public function checkPassportStatusByDays($expiryDate)
    {
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

    public function getPassportStatusString(?int $statusId): string
    {
        if ($statusId == 1) {
            return "Active";
        } else if ($statusId == 2) {
            return "Expired";
        } else if ($statusId == 3 || $statusId == 4) {
            return "TO be Renewed";
        }
        return '';
    }

    public function getType()
    {
        if ($this->type == 'D') {
            return "Diplomatic";
        } else if ($this->type == 'P') {
            return 'Personal';
        } else if ($this->type == 'O') {
            return 'Official';
        } else {
            return '';
        }
    }
}
