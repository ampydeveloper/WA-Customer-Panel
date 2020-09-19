<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerFarm extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id', 'farm_address', 'farm_city', 'farm_image', 'farm_province', 'farm_unit', 'farm_zipcode', 'farm_active', 'latitude', 'longitude', 'created_by'
    ];

    protected $hidden = ['deleted_at', 'updated_at'];

    public function managers()
    {
        return $this->hasMany('App\Models\User', 'farm_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'customer_id');
    }

    /**
     * @method isOwner:  Function to check if user is owner of map.
     * 
     */
    public function isOwner($userId = null)
    {
        $userId = ($userId) ? $userId : (\Auth::user()) ? \Auth::user()->id : null;
        
        return ($userId && $this->customer_id == $userId);
    }
}
