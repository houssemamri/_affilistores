<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTweet extends Model
{
    protected $fillable = [
        'product_id', 'tweet_id', 'user', 'content', 'user_profile_img'
    ];
}
