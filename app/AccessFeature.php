<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessFeature extends Model
{
    protected $fillable = [
        'membership_id', 'feature_id'
    ];

    public function features(){
        return $this->belongsTo('App\Feature', 'feature_id');
    }
}
