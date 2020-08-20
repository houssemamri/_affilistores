<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductHit extends Model
{
    protected $fillable = [
        'store_id', 'product_id', 'page_hits', 'affiliate_hits'
    ];

    public function product(){
        return $this->belongsTo('App\Product');
    }
}
