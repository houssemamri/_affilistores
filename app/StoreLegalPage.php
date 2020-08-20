<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreLegalPage extends Model
{
    protected $fillable = [
        'store_id', 'terms_conditions', 'privacy_policy', 'contact_us', 'gdpr_compliance', 'affiliate_disclosure', 'cookie_policy',
    ];
}
