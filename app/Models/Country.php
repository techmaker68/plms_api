<?php
// ***********************************
// @author Syed, Umair, Aqsa, Saqib, Majid
// @create_date 21-07-2023
// ***********************************
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Country extends Model
{
    use HasFactory , LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_code_2', 
        'country_code_3',
        'subregion_code',
        'region_code',
        'intermediate_region_code',
        'country_name_short_en',
        'country_name_full_en',
        'country_name_short_local',
        'country_name_full_local',
        'country_name_short_zh_cn',
        'country_name_full_zh_cn',
        'country_name_short_ar',
        'country_name_full_ar',
        'nationality_en',
        'nationality_zh_cn',
        'nationality_ar',
        'calling_code',
    ];
    protected static $logAttributes = ["*"];
    protected static $logOnlyDirty = true;

     /*
	 * get paxes
	 */
    public function paxes()
    {
        return $this->belongsTo(PLMSPax::class , 'id', 'nationality');
    }

     /*
	 * get paxes
	 */
    public function paxesCountryResidence()
    {
        return $this->belongsTo(PLMSPax::class , 'id', 'country_residence');
    }

    public function paxCountryCode()
    {
        return $this->hasOne(PLMSPax::class, 'id', 'country_code_id');
    }

    public function bloodApplicantCountryCode()
    {
        return $this->hasOne(PLMSBloodApplicant::class, 'id', 'country_code_id');
    }

     /*
	 * get paxes
	 */
    public function loiApplicantCountryResidence()
    {
        return $this->belongsTo(PLMSLOIApplicant::class , 'id', 'country_residence');
    }
    public function getCountryId($country)
    {
        $country = Country::where('country_code_2',  $country)->first();
        return $country ? $country->id : null;
    }
    public function extractCountryCode($country)
    {
        $viewParts = explode('-', $country);
        $firstPart = reset($viewParts);
        $firstPart = str_replace(chr(160), '', $firstPart);
        $firstPart = trim($firstPart);
        return $firstPart;
    }
   
}
