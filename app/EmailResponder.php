<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailResponder extends Model
{
    protected $fillable = [
        'name', 'description' ,'from', 'reply', 'to', 'subject', 'body'
    ];
}
