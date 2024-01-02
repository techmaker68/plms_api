<?php
// ***********************************
// @author Syed, Umair, Aqsa, Saqib, Majid
// @create_date 21-07-2023
// ***********************************
namespace Modules\Company\Entities;

use App\Models\Country;
use Modules\Loi\Entities\PLMSLoi;
use App\Traits\HandleFiles;
use Modules\Pax\Entities\PLMSPax;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PLMSCompany extends Model
{
    use HasFactory , LogsActivity, HandleFiles;
    protected $table = 'plms_companies';
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
        'name',
        'type',
        'status',
        'short_name',
        'industry',
        'email',
        'phone',
        'website',
        'address_1',
        'city',
        'country_id',
        'poc_name',
        'poc_email_or_username',
        'poc_mobile',
    ];

    /*
	 * get paxes
	 */
    public function paxes()
    {
        return $this->hasMany(PLMSPax::class , 'company_id');
    }

    public function lois()
    {
        return $this->hasMany(PLMSLoi::class , 'company_id');
    }

    /*
	 * get depart
	 */
    public function departments()
    {
        return $this->hasMany(PLMSDepartment::class , 'company_id');
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function getCountryEngName()
    {
        return $this->country->country_name_short_en ?? null;
    }

    public function scopeSearchByName($query, $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%");
    }
}
