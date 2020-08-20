<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use App\Setup;

class GlobalController extends Controller
{
    public function __construct() {
        $setups = Setup::all();
        $site = [];

        foreach ($setups as $setup) {
            $site[$setup->key] = $setup->value;
        }

        View::share ('site', $site);
    }  
}
