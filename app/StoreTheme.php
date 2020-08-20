<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreTheme extends Model
{
    protected $fillable = [
        'store_id', 'theme_id', 'color_scheme_id', 'favicon', 'footer_settings'
    ];

    public function theme(){
        return $this->belongsTo('App\Theme');
    }

    public function colorScheme(){
        return $this->belongsTo('App\ColorScheme');
    }
}
