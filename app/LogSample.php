<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogSample extends Model
{
    protected $fillable = [
        'store_id', 'time', 'text'
    ];
}
