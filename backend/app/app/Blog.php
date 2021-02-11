<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = ['comments', 'title', 'slug'];
    protected $casts = [
        'comments' => 'json',
    ];
}
