<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacebookCustomerChat extends Model
{
    protected $fillable = [
        'store_id', 'code'
    ];
}
