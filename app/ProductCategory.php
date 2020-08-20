<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $fillable = [
        'category_id', 'product_id'
    ];

    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function product(){
        return $this->belongsTo('App\Product');
    }
}
