<?php

namespace App\Models\Traits\User;

use Storage;

trait UserMethods
{
    public function isCustomer()
    {
        return ($this->role_id == config('constant.roles.Customer') || $this->role_id == config('constant.roles.haulers'));
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
        $imageName = ($imageName) ? $imageName : time().'.'.$image->extension();

        return (Storage::disk('user_images')->put($this->id.'/'.$imageName, file_get_contents($image))) ? $imageName : false;
    }
}
