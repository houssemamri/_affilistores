<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $fillable = [
        'store_id', 'title', 'description'
    ];

    public function blogs(){
        return $this->hasMany('App\Blog');
    }

    public function feeds(){
        return $this->hasMany('App\Feed');
    }
}
