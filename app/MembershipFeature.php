<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipFeature extends Model
{
    protected $fillable = [
        'membership_id', 'feature'
    ];
}
