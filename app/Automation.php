<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Automation extends Model
{
    protected $fillable = [
        'store_id', 'source', 'category', 'keyword', 'number_daily_post', 'start_date', 'end_date', 'product_data'
    ];


    public function store(){
        return $this->belongsTo('App\Store');
    }
}
