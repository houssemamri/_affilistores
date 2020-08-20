<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'store_id', 'name', 'description', 'permalink', 'image', 'meta_title', 'meta_description', 'meta_keywords', 'robots_meta_no_index', 'robots_meta_no_follow', 'status'
    ];
    
    public function productCategory(){
        return $this->hasMany('App\ProductCategory');
    }
}
