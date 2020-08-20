<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WarriorPlusProduct extends Model
{
    protected $fillable = [
        'offer_name',
        'offer_date', 
        'offer_code', 
        'offer_url', 
        'vendor_name', 
        'vendor_url',
        'allow_affiliates',
        'request_url',
        'has_recurring',
        'has_contest',
        'sales_range',
        'conv_rate',
        'refund_rate',
        'visitor_value',
        'pulse_score',
    ]; 
}
