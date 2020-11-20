<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class CustomerFarm extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'customer_id', 'farm_address', 'farm_city', 'farm_image', 'farm_province', 'farm_unit', 'farm_zipcode', 'farm_active', 'latitude', 'longitude', 'distance', 'created_by'
    ];

    protected $hidden = ['deleted_at', 'updated_at'];

    protected $appends = ['full_address', 'primary_manager', 'total_jobs', 'manager_details'];

    public function managers()
    {
        return $this->hasMany('App\Models\User', 'farm_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'customer_id');
    }

    public function jobs()
    {
        return $this->hasMany('App\Models\Job', 'farm_id')->with('customer', 'manager');
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

    public function putImage($image, $imageName = null)
    {
        $imageName = ($imageName) ? $imageName : rand().time().'.'.$image->extension();
        $path = $this->customer_id.'/farms/'.$this->id.'/'.$imageName;
        
        return (Storage::disk('user_images')->put($path, file_get_contents($image))) ? Storage::disk('user_images')->url($path) : false;
    }

    public function getFullAddressAttribute()
    {
        return $this->farm_address.' '.$this->farm_city.' '.$this->farm_province.' '.$this->farm_zipcode;
    }

    public function getPrimaryManagerAttribute()
    {
        return $this->managers()->first();
    }

    public function getTotalJobsAttribute()
    {
        return $this->jobs()->count();
    }

    public function getManagerDetailsAttribute(){
        $managersWPrefix = [];
        foreach ($this->managers()->get() as $manager) {
            $manager['manager_first_name'] = $manager['first_name'];
            $manager['manager_last_name'] = $manager['last_name'];
            $manager['manager_phone'] = $manager['phone'];
            $manager['manager_address'] = $manager['address'];
            $manager['manager_city'] = $manager['city'];
            $manager['manager_province'] = $manager['state'];
            $manager['manager_zipcode'] = $manager['zip_code'];
            $manager['manager_id_card'] = $manager['managerDetails']['identification_number'];
            $manager['salary'] = $manager['managerDetails']['salary'];
            unset($manager['managerDetails']);
            array_push($managersWPrefix, $manager);
        }
        return $managersWPrefix;
    }
}
