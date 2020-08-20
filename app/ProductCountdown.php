<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCountdown extends Model
{
    protected $fillable = [
        'store_id', 'product_id', 'name', 'description', 'countdown_date', 'access_link', 'settings'
    ];

    public function product(){
        return $this->belongsTo('App\Product');
    }
}
