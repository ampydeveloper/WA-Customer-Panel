<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\User\{
    UserRelationship,
    UserAttributes,
    UserMethods
};
use App\Models\CustomerFarm;
use Storage;
class User extends Authenticatable
{
    use Notifiable, HasApiTokens, UserRelationship, UserAttributes, UserMethods;
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
        'password', 'remember_token', 'deleted_at', 'updated_at'
    ];

    protected $appends = ['full_name', 'image_url'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
