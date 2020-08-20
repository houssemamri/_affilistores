<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Autoresponder extends Model
{
    protected $fillable = [
        'store_id', 'name', 'settings'
    ];
}
