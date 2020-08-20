<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketPlaceInstruction extends Model
{
    protected $fillable = [
        'market_place', 'instructions'
    ];
}
