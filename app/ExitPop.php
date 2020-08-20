<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExitPop extends Model
{
    protected $fillable = [
        'store_id', 'name', 'heading', 'body', 'image', 'content', 'button_text', 'status', 'styles'
    ];
}
