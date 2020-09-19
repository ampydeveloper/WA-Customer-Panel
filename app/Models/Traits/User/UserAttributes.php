<?php

namespace App\Models\Traits\User;

trait UserAttributes
{
    public function getFullNameAttribute()
    {
        return ucwords(trim($this->first_name . ' '. $this->last_name));
    }
}
