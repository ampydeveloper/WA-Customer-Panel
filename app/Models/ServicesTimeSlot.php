<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicesTimeSlot extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'services_id', 'slot_type', 'slot_start', 'slot_end'
    ];
}
