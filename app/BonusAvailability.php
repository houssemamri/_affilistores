<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BonusAvailability extends Model
{
    protected $fillable = [
        'bonus_id', 'membership_id'
    ];

    public function bonus(){
        return $this->belongsTo('App\Bonus');
    }

    public function membership(){
        return $this->belongsTo('App\Membership');
    }
}
