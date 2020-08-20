<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'email', 'password', 'active', 'role_id', 'member_detail_id', 'access_right_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role(){
        return $this->belongsTo('App\Role');
    }

    public function detail(){
        return $this->belongsTo('App\MemberDetail', 'member_detail_id');
    }
}
