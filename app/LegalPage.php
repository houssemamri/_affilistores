<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LegalPage extends Model
{
    protected $fillable = [
        'store_id', 'terms_conditions', 'privacy_policy', 'contact_us'
    ];
}
