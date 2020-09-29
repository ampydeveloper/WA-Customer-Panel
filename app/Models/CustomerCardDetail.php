<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerCardDetail extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'customer_id', 'card_id', 'card_number', 'card_exp_month', 'card_exp_year', 'card_status', 'card_primary'
    ];

    protected  $hidden = ['card_number'];

    protected $appends = ['last_four'];

    public function getLastFourAttribute()
    {
       return ($this->card_number) ? substr($this->card_number, -4) : null;
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\User', 'customer_id', 'id');
    }
}
