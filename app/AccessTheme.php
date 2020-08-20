<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessTheme extends Model
{
    protected $fillable = [
        'membership_id', 'theme_id'
    ];

    public function theme(){
        return $this->belongsTo('App\Theme');
    }
}
