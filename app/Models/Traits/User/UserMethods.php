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
        return config('constant.base_url').'/10556652611610387832.jpeg';
    }

    public function putImage($image, $imageName = null)
    {
        $imageName = ($imageName) ? $imageName : rand().time().'.'.$image->extension();
        if($this->id != null) {
            if (Storage::disk('public')->put($this->id . '/' . $imageName, file_get_contents($image))) {
                $imageName = config('constant.base_url') . '/' . $this->id . '/' . $imageName;
            } else {
                $imageName = false;
            }
        } else {
            if (Storage::disk('public')->put($imageName, file_get_contents($image))) {
                $imageName = config('constant.base_url') . '/' . $imageName;
            } else {
                $imageName = false;
            }
        }

        return $imageName; 
    }

    public function defaultCard()
    {
        return CustomerCardDetail::where('customer_id', $this->id)->where('card_primary', 1)->first();
    }

    public function myJobs($page_no=null)
    {
        if ($this->isCustomer()) {
            $jobs = Job::where('customer_id', $this->id)->with('farm', 'customer', 'manager', 'service');
        } elseif($this->role_id == config('constant.roles.Customer_Manager')) {
            $jobs = Job::where('manager_id', $this->id)->orWhere('farm_id', $this->farm_id)->with('farm','customer', 'manager', 'service');
        } else {
            $jobs = Job::where('manager_id', $this->id)->with('farm','customer', 'manager', 'service');
        }
        if($page_no != null){
            $size = 20;
            $skip = ($page_no - 1) * $size;
            $jobs = $jobs->skip($skip)->take($size);
        }
        return $jobs->get();
    }
    
    public function myUpcomingJobs($page_no=null)
    {
        if ($this->isCustomer()) {
            $jobs = Job::where('customer_id', $this->id)->where('job_providing_date', '>', Carbon::now())->with('farm','customer', 'manager', 'service');
        } elseif($this->role_id == config('constant.roles.Customer_Manager')) {
            $jobs = Job::where('manager_id', $this->id)->orWhere('farm_id', $this->farm_id)->where('job_providing_date', '>', Carbon::now())->with('farm','customer', 'manager', 'service');
        }else {
            $jobs = Job::where('manager_id', $this->id)->where('job_providing_date', '>', Carbon::now())->with('farm','customer', 'manager', 'service');
        }
        if($page_no != null){
            $size = 20;
            $skip = ($page_no - 1) * $size;
            $jobs = $jobs->skip($skip)->take($size);
        }
        return $jobs->get();
    }
}
