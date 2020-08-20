<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ColorScheme extends Model
{
    protected $fillable = [
        'theme_id', 'name', 'slug'
    ];

    public function theme(){
        return $this->belongsTo('App\Theme');
    }

    public function access(){
        return $this->belongsTo('App\AccessColorScheme');
    }
}
