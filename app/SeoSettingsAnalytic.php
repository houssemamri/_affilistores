<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeoSettingsAnalytic extends Model
{
    protected $fillable = [
        'store_id', 'google_analytics_tracking_code', 'third_party_analytics_tracking_code', 'facebook_remarketing_pixel_script', 'webengage_tracking_id'
    ];
}
