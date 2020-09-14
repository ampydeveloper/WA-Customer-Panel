<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\User\{
    UserRelationship
};
use App\Models\CustomerFarm;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, UserRelationship;
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

    public function isCustomer()
    {
        return ($this->role_id == config('constant.roles.Customer') || $this->role_id == config('constant.roles.haulers'));
    }


    public function canAccessFarm($farmId)
    {
     
        $farm = (get_class($farmId) == 'App\Models\CustomerFarm') ? $farmId :CustomerFarm::find($farmId);
        if (!$farm) {
            return false;
        }
        if ($this->isCustomer() && $farm->customer_id == $this->id) {
            return true;
        } else if ($this->role_id == config('constant.roles.Customer_Manager') && $farm->id == $this->farm_id) {
            return true;
        }

        return false;
    }
}
