<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductSeoSetting extends Model
{
    protected $fillable = [
        'product_id', 'meta_title', 'meta_description', 'meta_keywords', 'robots_meta_no_index', 'robots_meta_no_follow', 'status'
    ];
}
