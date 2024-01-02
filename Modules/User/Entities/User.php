<?php
// ***********************************
// @author Syed, Umair, Aqsa, Saqib, Majid
// @create_date 21-07-2023
// ***********************************
namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Country;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Laravel\Passport\HasApiTokens;
use Modules\Company\Entities\PLMSCompany;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles , LogsActivity, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $table = 'users';
    protected static $logAttributes = ["*"];
    protected static $logOnlyDirty = true;
    protected $guard_name = 'api';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*
	 * Get full name
	 */
    public function getName()
    {
        return ($this->name ? $this->name : '') . ' ' . ($this->last_name ? $this->last_name : '');
    }

    /*
	 * Get company
	 */
    public function company()
    {
        return $this->hasOne(PLMSCompany::class, 'id', 'company_id');
    }


    public function plms_user_company()
    {
        return $this->hasOne(PLMSCompany::class, 'id', 'company_id');
    }
   
    public function countrydata()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('email', 'LIKE', "%{$searchTerm}%");
    }

    public function rolesWithoutPivot()
    {
        return $this->roles->map(function ($role) {
            return $role->only(['id', 'name', 'display_name', 'created_at']);
        });
    }
  
}
