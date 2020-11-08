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
        return ($this->user_image) ? Storage::disk('user_images')->url('/user_images/'.$this->id.'/'.$this->user_image) : $this->defaultImageUrl();
    }
}
