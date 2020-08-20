<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeoSettingsWebmastersSetting extends Model
{
    protected $fillable = [
        'store_id', 'google_verification_code', 'bing_verification_code', 'pinterest_verification_code'
    ];
}
