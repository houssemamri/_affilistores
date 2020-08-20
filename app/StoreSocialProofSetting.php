<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreSocialProofSetting extends Model
{
    protected $fillable = [
        'store_id', 'settings'
    ];
}
