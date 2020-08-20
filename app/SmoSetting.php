<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmoSetting extends Model
{
    protected $fillable = [
        'store_id', 'name', 'page_url', 'design_options', 'display_options'
    ];
}
