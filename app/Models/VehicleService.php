<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleService extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vehicle_id', 'service_date', 'service_killometer', 'receipt', 'document', 'note', 'created_by'
    ];

    public function vehicle()
    {
        return $this->belongsTo('App\Models\Vehicle');
    }
}
