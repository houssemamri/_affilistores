<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessColorScheme extends Model
{
    protected $fillable = [
        'membership_id', 'color_scheme_id'
    ];

    public function colorScheme(){
        return $this->belongsTo('App\ColorScheme');
    }
}
