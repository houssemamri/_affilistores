<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialSetting extends Model
{
    protected $fillable = [
        'store_id', 'name', 'settings',
    ];
}
