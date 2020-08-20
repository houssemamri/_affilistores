<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialCampaign extends Model
{
    protected $fillable = [
        'store_id', 'name', 'category_id', 'enable_autopost', 'schedule_date', 'schedule_time', 'products', 'is_posted'
    ];

    public function campaigns(){
        return $this->hasMany('App\Campaign');
    }
}
