<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeoSettingsProductPage extends Model
{
    protected $fillable = [
        'store_id', 'meta_title', 'robots_meta_no_index', 'robots_meta_no_follow'
    ];
}
