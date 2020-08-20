<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacebookCampaign extends Model
{
    protected $fillable = [
        'social_campaign_id', 'facebook_group_id', 'content'
    ];
}
