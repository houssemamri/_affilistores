<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use Session;
use Crypt;
use Route;
use Purifier;
use Auth;
use App\Store;
use App\Bonus;
use App\BonusAvailability;

class BonusesController extends GlobalController
{
    private $store;

    public function __construct(){
        parent::__construct();
        
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();
    }

    public function index(){
        $store = $this->store;
        $bonuses = BonusAvailability::where('membership_id', Auth::user()->memberDetail->membership_id)->get();
        return view('bonuses.index', compact('bonuses', 'store'));
    }

    public function show($subdomain, $id){
        $store = $this->store;
        $decrypted = Crypt::decrypt($id);
        $bonus = Bonus::find($decrypted);

        return view('bonuses.show', compact('bonus', 'store'));
    }

    public function ecoverCreate($subdomain, $name, $id){
        $store = $this->store;

        return view('bonuses.e-cover-creator.index', compact('store', 'name', 'id'));
    }

    public function sgp(){
        $store = $this->store;

        return view('bonuses.sale-graphic-producer.index', compact('store', 'name', 'id'));
    }

}
