<?php

namespace App\Models\Traits\User;
use App\Models\CustomerCardDetail;
use App\Models\Job;
use Carbon\Carbon;
use Storage;

trait UserMethods
{
    public function isCustomer()
    {
        return ($this->role_id == config('constant.roles.Customer') || $this->role_id == config('constant.roles.Haulers'));
    }

    public function canAccessFarm($farmId)
    {
        $farm = (get_class($farmId) == 'App\Models\CustomerFarm') ? $farmId : CustomerFarm::find($farmId);
        if (!$farm) {
            return false;
        }
        if ($this->isCustomer() && $farm->customer_id == $this->id) {
            return true;
        } elseif ($this->role_id == config('constant.roles.Customer_Manager') && $farm->id == $this->farm_id) {
            return true;
        }

        return false;
    }

    /**
     * @method getAllFarms : Function to get all farms of current model.
     *
     */
    public function getAllFarms()
    {
    }

    public function defaultImageUrl()
    {
        return Storage::disk('public')->url('default-user.jpg');
    }

    public function putImage($image, $imageName = null)
    {
        $imageName = ($imageName) ? $imageName : rand().time().'.'.$image->extension();

        return (Storage::disk('user_images')->put($this->id.'/'.$imageName, file_get_contents($image))) ? $imageName : false;
    }

    public function defaultCard()
    {
        return CustomerCardDetail::where('customer_id', $this->id)->where('card_primary', 1)->first();
    }

    public function myJobs()
    {
        if ($this->isCustomer()) {
            return Job::where('customer_id', $this->id)->with('farm','customer', 'manager', 'service')->get();
        } else {
            $farm = $this->managerOf;
            if (!$farm) {
                return [];
            }

            return Job::where('farm_id', $farm->id)->with('farm','customer', 'manager', 'service')->get();
        }
    }
    
    public function myUpcomingJobs()
    {
        if ($this->isCustomer()) {
            return Job::where([
                'customer_id' => $this->id
            ])->where('job_providing_date', '>', Carbon::now())
            ->with('farm','customer', 'manager', 'service')
            ->get();
        } else {
            $farm = $this->managerOf;
            if (!$farm) {
                return [];
            }

            return Job::where([
                'farm_id' => $farm->id
            ])->where('job_providing_date', '>', Carbon::now())
            ->with('farm','customer', 'manager', 'service')
            ->get();
        }
        
    }
}
