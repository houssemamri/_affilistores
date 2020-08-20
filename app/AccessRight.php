<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessRight extends Model
{

    protected $fillable = [
        'member_menu_id', 'membership_id'
    ];

    public function menu(){
        return $this->belongsTo('App\MemberMenu', 'member_menu_id');
    }
}
