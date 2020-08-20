<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialProof extends Model
{
    protected $fillable = [
        'store_id', 'content', 'type', 'order', 'active'
    ];

    public function product(){
        return $this->belongsTo('App\Product');
    }

    public function store(){
        return $this->belongsTo('App\Store');
    }
}
