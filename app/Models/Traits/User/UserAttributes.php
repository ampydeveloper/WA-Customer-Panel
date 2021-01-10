<?php

namespace App\Models\Traits\User;
use Storage;

trait UserAttributes
{
    public function getFullNameAttribute()
    {
        return ucwords(trim($this->first_name . ' '. $this->last_name));
    }

    public function getImageUrlAttribute()
    {
        return ($this->user_image) ? $this->user_image : $this->defaultImageUrl();
    }
}
