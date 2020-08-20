<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeoSettingsHomePage extends Model
{
    protected $fillable = [
        'store_id', 'website_name', 'meta_title', 'meta_description', 'meta_keywords', 'robots_meta_no_index', 'robots_meta_no_follow'
    ];
}
