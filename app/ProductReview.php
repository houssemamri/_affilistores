<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = [
        'product_id', 'name', 'email', 'review', 'ratings', 'approved'
    ];

    public function product(){
        return $this->belongsTo('App\Product');
    }
}
