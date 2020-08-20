<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'main_tagline', 
        'main_tagline_font_size', 
        'sub_tagline', 
        'sub_tagline_font_size', 
        'cta_button_one_text', 
        'cta_button_one_link', 
        'cta_button_two_text', 
        'cta_button_two_link', 
        'image', 
        'status'
    ];
}
