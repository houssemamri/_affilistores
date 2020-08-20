<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayDotComProduct extends Model
{
    protected $fillable = [
        'reference_id',
        'name',
        'description',
        'image',
        'payout_type',
        'preview_url',
        'payout',
        'categories',
        'request_url',
        'recurring',
        'recurring_in_funnel'
    ];
}
