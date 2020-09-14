<?php

namespace App\Models\Traits\User;

trait UserRelationship
{
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
