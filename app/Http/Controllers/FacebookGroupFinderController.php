<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;

class FacebookGroupFinderController extends GlobalController
{
    private $store;

    public function __construct(){
        parent::__construct();

        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();
    }
    
    
}
