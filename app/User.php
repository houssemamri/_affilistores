<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'active', 'last_login'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role(){
        return $this->belongsTo('App\Role');
    }

    public function detail(){
        return $this->hasOne('App\UserDetail');
    }

    public function memberDetail(){
        return $this->hasOne('App\MemberDetail');
    }

    public function accessRight(){
        return $this->hasMany('App\AccessRight');
    }

    public function stores(){
        return $this->hasMany('App\Store');
    }

    public function logins(){
        return $this->hasMany('App\Audit')->orderBy('id', 'DESC');
    }

    public function getLastLogin(){
        return $this->logins()->orderBy('id', 'DESC')->take(1);
    }
}
