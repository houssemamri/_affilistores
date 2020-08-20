<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = [
        'question', 'total_vote', 'status'
    ];

    public function options(){
        return $this->hasMany('App\PollOption');
    }

    public function votes(){
        return $this->hasMany('App\PollVote');
    }
}
