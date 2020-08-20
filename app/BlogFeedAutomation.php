<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogFeedAutomation extends Model
{
    protected $fillable = [
        'blog_feed_id', 'from', 'to', 'frequency', 'auto_publish'
    ];


    public function blogFeed(){
        return $this->belongsTo('App\BlogFeed', 'blog_feed_id');
    }
}
