<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageAvailability extends Model
{
    protected $fillable = [
        'page_id', 'membership_id'
    ];

    public function page(){
        return $this->belongsTo('App\Page');
    }

    public function membership(){
        return $this->belongsTo('App\Membership');
    }
}
