<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'driver_type', 'driver_licence', 'expiry_date', 'document', 'salary_type', 'driver_salary'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
