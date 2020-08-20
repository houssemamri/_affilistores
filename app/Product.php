<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'store_id', 'reference_id', 'name', 'description', 'permalink', 'details_link', 'image', 'price', 'currency', 'source', 'published_date', 'status', 'auto_approve', 'show_tweets'
    ];

    public function store(){
        return $this->belongsTo('App\Store');
    }

    public function categories(){
        return $this->hasMany('App\ProductCategory');
    }

    public function tags(){
        return $this->hasMany('App\ProductTag');
    }

    public function images(){
        return $this->hasMany('App\ProductImage');
    }

    public function videos(){
        return $this->hasMany('App\ProductVideo');
    }

    public function seoSettings(){
        return $this->hasOne('App\ProductSeoSetting');
    }

    public function tweets(){
        return $this->hasMany('App\ProductTweet');
    }

    public function hits(){
        return $this->hasMany('App\ProductHit');
    }

    public function reviews(){
        return $this->hasMany('App\ProductReview');
    }

    public function blog(){
        return $this->hasOne('App\ProductBlog');
    }
}
