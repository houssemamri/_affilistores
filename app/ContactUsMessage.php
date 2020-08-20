<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactUsMessage extends Model
{
    protected $fillable = [
        'store_id', 'name', 'email', 'subject', 'message'
    ];
}
