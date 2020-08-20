<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AffiliateSetting extends Model
{
    protected $fillable = [
        'store_id', 'name', 'settings'
    ];

}
