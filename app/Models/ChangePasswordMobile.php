<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChangePasswordMobile extends Model {
    protected $table = 'change_password_mobile';
    protected $fillable = ['user_id','otp','expired'];
}



