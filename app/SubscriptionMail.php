<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionMail extends Model
{
    protected $fillable = [
        'store_id', 'subject', 'body'
    ];

    public function sents(){
        return $this->hasMany('App\SubscriptionSent');
    }
}
