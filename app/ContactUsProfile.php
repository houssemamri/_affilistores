<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactUsProfile extends Model
{
    protected $fillable = [
        'store_id', 'email', 'phone', 'address', 'city', 'state', 'country'
    ];
}
