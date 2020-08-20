<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'store_id', 'social_campaign_id', 'type'
    ];
}
