<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionSent extends Model
{
    protected $fillable = [
        'store_id', 'subscriber_id', 'subscription_mail_id'
    ];
}
