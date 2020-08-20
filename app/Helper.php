<?php
    /**
    * change plain number to formatted currency
    *
    * @param $number
    * @param $currency
    */
    function currency($code)
    {
        $currency = [
            'AUD' => '$',
            'BRL' => 'R$',
            'CAD' => '$',
            'CNY' => '¥',
            'FRF' => 'F',
            'EUR' => '€',
            'INR' => '₹',
            'JPY' => '¥',
            'MXN' => '$',
            'TRY' => '₺',
            'GBP' => '£',
            'USD' => '$'
        ];

        return isset($currency[$code]) ? $currency[$code] : '$';
    }