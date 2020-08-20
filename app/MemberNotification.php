<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MemberNotification extends Model
{
    protected $fillable = [
        'subject', 'body'
    ];

    public function views(){
        return $this->hasMany('App\MemberNotificationView');
    }
}
