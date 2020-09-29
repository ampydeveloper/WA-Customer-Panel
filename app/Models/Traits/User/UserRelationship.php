<?php

namespace App\Models\Traits\User;

trait UserRelationship
{
    public function farms()
    {
        if ($this->role_id == config('constant.roles.Customer_Manager')) {
            return $this->hasMany('App\Models\CustomerFarm', 'id', 'farm_id');
        } else {
            return $this->hasMany('App\Models\CustomerFarm', 'customer_id');
        }
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function managerOf()
    {
        return $this->hasOne('App\Models\CustomerFarm', 'id', 'farm_id');
    }

    public function managerDetails()
    {
        return $this->hasOne('App\Models\ManagerDetail', 'user_id', 'id');
    }

    public function cards()
    {
        return $this->hasMany('App\Models\CustomerCardDetail', 'customer_id', 'id');
    }
}
