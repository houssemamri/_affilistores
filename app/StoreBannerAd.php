<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreBannerAd extends Model
{
    protected $fillable = [
        'store_id', 'type', 'content', 'selected'
    ];
}
