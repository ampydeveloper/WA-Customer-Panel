<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use SoftDeletes;

    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    protected $casts = [
        'options' => 'json',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_created_by','customer_id', 'manager_id', 'farm_id', 'gate_no', 'service_id', 'time_slots_id', 'job_providing_date', 'weight', 'is_repeating_job', 'repeating_days', 'images',
        'notes', 'amount', 'payment_mode', 'job_status', 'payment_status', 'quick_book', 'truck_id', 'truck_driver_id', 'skidsteer_id', 'skidsteer_driver_id', 'start_time', 'end_time'
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\User', 'customer_id', 'id');
    }

    public function manager()
    {
        return $this->belongsTo('App\Models\User', 'manager_id', 'id');
    }

    public function farm()
    {
        return $this->belongsTo('App\Models\CustomerFarm', 'farm_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo('App\Models\Service', 'service_id', 'id');
    }

    public function truck()
    {
        return $this->belongsTo('App\Models\Vehicle', 'truck_id', 'id');
    }

    public function skidsteer()
    {
        return $this->belongsTo('App\Models\Vehicle', 'skidsteer_id', 'id');
    }

    public function truck_driver()
    {
        return $this->belongsTo('App\Models\User', 'truck_driver_id', 'id');
    }

    public function skidsteer_driver()
    {
        return $this->belongsTo('App\Models\User', 'skidsteer_driver_id', 'id');
    }

    public function timeslots()
    {
        return $this->belongsTo('App\Models\TimeSlots', 'time_slots_id', 'id');
    }

    public function jobpayment()
    {
        return $this->hasOne('App\Models\Payment', 'job_id');
    }

    public function employeeSalaries()
    {
        return $this->hasOne('App\Models\EmployeeSalaries', 'user_id');
    }
}