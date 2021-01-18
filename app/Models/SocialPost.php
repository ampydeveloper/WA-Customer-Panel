<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialPost extends Model
{
    use SoftDeletes;
 
    protected $table = 'social_posts';
    protected $fillable = [
        'media_type', 'media_url', 'username', 'timestamp'
    ];
}