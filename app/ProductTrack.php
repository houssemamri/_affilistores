<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductTrack extends Model
{
    protected $fillable = [
        'store_id', 'reference_id', 'source', 'status'
    ];
}
