<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacebookGroup extends Model
{
    protected $fillable = [
        'store_id', 'name', 'image', 'group_id', 'privacy'
    ];
}
