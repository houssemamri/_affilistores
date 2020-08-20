<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberDetail extends Model
{
    protected $fillable = [
        'user_id', 'membership_id', 'expiry_date', 
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function member(){
        return $this->hasOne('App\Member');
    }

    public function membership(){
        return $this->belongsTo('App\Membership');
    }
}
