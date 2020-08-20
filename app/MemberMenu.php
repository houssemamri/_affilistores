<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberMenu extends Model
{
    protected $fillable = [
        'title', 'slug', 'icon', 'order'
    ];

    public function pages(){
        return $this->hasMany('App\Page', 'menu_id');
    }
}
