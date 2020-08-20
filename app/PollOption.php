<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    protected $fillable = [
        'poll_id', 'name'
    ];

    public function votes(){
        return $this->hasMany('App\PollVote');
    }
}
