<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes;
 
    protected $table = 'news';
    protected $fillable = [
        'description', 'heading', 'image', 'slug'
    ];
}