<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_id', 'user_id', 'customer_id', 'payment_id', 'payment_mode', 'payment_method', 'currency', 'amount', 'payment_status'
    ];

    public function job()
    {
        return $this->belongsTo('App\Models\Job', 'job_id', 'id');
    }
}
