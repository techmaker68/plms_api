<?php
// ***********************************
// @author Syed, Umair, Aqsa, Saqib, Majid
// @create_date 21-07-2023
// ***********************************
namespace Modules\Department\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Company\Entities\PLMSCompany;
use Modules\Pax\Entities\PLMSPax;
use Spatie\Activitylog\Traits\LogsActivity;

class PLMSDepartment extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'plms_departments';
    protected static $logAttributes = ["*"];
    protected static $logOnlyDirty = true;
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'company_id',
        'manager_name',
        'abbreviation',
    ];

    /*
	 * get paxes
	 */
    public function company()
    {
        return $this->belongsTo(PLMSCompany::class, 'company_id');
    }

    /*
	 * get paxes
	 */
    public function paxes()
    {
        return $this->hasMany(PLMSPax::class, 'department_id');
    }

    public function getCompanyName()
    {
        return $this->company->name ?? null;
    } 
    
    public function scopeSearchDetails($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('manager_name', 'LIKE', "%{$search}%")
                ->orWhere('abbreviation', 'LIKE', "%{$search}%")
                ->orWhereHas('company', function ($companyQuery) use ($search) {
                    $companyQuery->searchByName($search);
                });
        });
    }
}
