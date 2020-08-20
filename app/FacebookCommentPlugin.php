<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacebookCommentPlugin extends Model
{
    protected $fillable = [
        'store_id', 'sdk_code', 'code_snippet'
    ];
}
