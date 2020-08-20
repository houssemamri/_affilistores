<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogFeed extends Model
{
    protected $fillable = [
        'store_id', 'url', 'blog_category_id', 'category_id'
    ];

    public function category(){
        return $this->belongsTo('App\BlogCategory', 'blog_category_id');
    }

    public function automation(){
        return $this->hasOne('App\BlogFeedAutomation', 'blog_feed_id');
    }
}
