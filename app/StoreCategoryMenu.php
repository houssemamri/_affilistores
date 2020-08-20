<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreCategoryMenu extends Model
{
    protected $fillable = [
        'store_id', 'category_id', 'order'
    ];

    public function category(){
        return $this->belongsTo('App\Category');
    }
}
