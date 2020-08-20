<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'store_id', 'title', 'post', 'type', 'url', 'blog_category_id', 'category_id', 'slug', 'published'
    ];

    public function category(){
        return $this->belongsTo('App\BlogCategory', 'blog_category_id');
    }

    public function productCategory(){
        return $this->belongsTo('App\Category', 'category_id');
    }
}