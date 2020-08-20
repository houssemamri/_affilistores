<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GetResponseSetting extends Model
{
    protected $fillable = [
        'store_id', 'settings'
    ];
}
