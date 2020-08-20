<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreSlider extends Model
{
    protected $fillable = [
        'store_id', 'slider_id', 'slider_number'
    ];

    public function slider(){
        return $this->belongsTo('App\Slider');
    }

    public function enabledSlider() {
        return $this->slider()->where('status', 1);
    }
}
