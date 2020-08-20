<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductBlog extends Model
{
    protected $fillable = [
        'store_id', 'product_id', 'title', 'description', 'published'
    ];
}
