<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClickBankProduct extends Model
{
    protected $fillable = [
        'reference_id', 
        'category',
        'popularity_rank', 
        'title', 
        'description', 
        'has_recurring_products', 
        'gravity', 
        'percent_per_sale', 
        'percent_per_rebill', 
        'average_earnings_per_sale', 
        'initial_earnings_per_sale', 
        'total_rebill_amt',
        'referred',
        'commission',
        'activate_date'
    ];
}
