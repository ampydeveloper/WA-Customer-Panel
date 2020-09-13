<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'created_by', 'vehicle_type', 'company_name', 'truck_number', 'chaase_number', 'killometer', 'capacity', 'document',  'status',
        'assigned_job_row_action_count'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_by', 'id');
    }

    public function vehicle_service()
    {
        return $this->hasOne('App\Models\VehicleService');
    }

    public function vehicle_insurance()
    {
        return $this->hasOne('App\Models\VehicleInsurance');
    }
    
    public function vehicle_insurances()
    {
        return $this->hasMany('App\Models\VehicleInsurance');
    }
}
