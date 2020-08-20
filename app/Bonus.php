<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    protected $fillable = [
        'name', 'description', 'image', 'file', 'size'
    ];

    public function available(){
        return $this->hasMany('App\BonusAvailability');
    }
}
