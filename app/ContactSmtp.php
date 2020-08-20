<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactSmtp extends Model
{
    protected $fillable = [
        'store_id', 'host', 'port', 'username', 'password', 'encryption'
    ];
}
