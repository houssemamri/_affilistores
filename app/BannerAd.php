<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BannerAd extends Model
{
    protected $fillable = [
        'type', 'content'
    ];
}
