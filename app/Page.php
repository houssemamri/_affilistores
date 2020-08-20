<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
       'menu_id', 'title', 'body', 'slug', 'type', 'page_part', 'order', 'icon'
    ];

    public function available(){
        return $this->hasMany('App\PageAvailability');
    }

    public function menu(){
        return $this->belongsTo('App\MemberMenu');
    }
}
