<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeoSettingsTitleSetting extends Model
{
    protected $fillable = [
        'store_id', 'search_page_title', 'error_page_title'
    ];
}
