<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialCampaignLog extends Model
{
    protected $fillable = [
        'store_id', 'product_id', 'posted_to', 'link', 'social_link', 'status', 'posted_date'
    ];

    public function product(){
       return $this->belongsTo('App\Product'); 
    }
}
