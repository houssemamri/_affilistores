<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'phone', 'address', 'country', 'state', 'city', 'avatar'
    ];
}
