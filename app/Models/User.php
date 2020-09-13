<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prefix','first_name', 'last_name', 'email', 'phone','address', 'city', 'state', 'zip_code', 'user_image', 'role_id','created_by', 'created_from', 'is_confirmed', 'is_active', 'provider', 'token', 'password',
         'country', 'password_changed_at',   'farm_id',
    ];

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

    public function managerDetails()
    {
        return $this->hasOne('App\Models\ManagerDetail');
    }

    //self relationship
    public function customerManager()
    {
        return $this->hasMany('App\Models\User', 'created_by');
    }

    public function farms()
    {
        return $this->hasOne('App\Models\CustomerFarm', 'customer_id');
    }

    public function farmlist()
    {
        return $this->hasMany('App\Models\CustomerFarm', 'customer_id');
    }
    
    public function manager_farms()
    {
        return $this->hasOne('App\Models\CustomerFarm', 'id', 'farm_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function driver()
    {
        return $this->hasOne('App\Models\Driver');
    }

    public function jobTruckDriver()
    {
        return $this->hasOne('App\Models\Job', 'truck_driver_id');
    }

    public function jobSkidsteerDriver()
    {
        return $this->hasOne('App\Models\Job', 'skidsteer_driver_id');
    }

    public function employeeSalaries()
    {
        return $this->hasOne('App\Models\EmployeeSalaries', 'user_id');
    }

    
}
