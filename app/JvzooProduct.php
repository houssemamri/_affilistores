<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JvzooProduct extends Model
{
    protected $fillable = [
        'reference_id',
        'product_name',
        'product_commission',
        'vendor_name',
        'launch_date_time',
        'affiliate_info_page',
        'sales_page',
        'product_sales',
        'product_refund_rate',
        'product_conversion',
        'product_epc',
        'product_average_price',
        'funnel_sales',
        'funnel_refund_rate',
        'funnel_conversion',
        'funnel_epc',
        'funnel_average_price'
    ];
    
}
