<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\GlobalController;
use Input;
use Session;
use SimpleXMLElement;
use Route;
use Crypt;
use Purifier;
use Response;
use Thujohn\Twitter\Facades\Twitter;
use Carbon\Carbon;
use App\Store;
use App\SocialCampaign;
use App\Category;

class FacebookGroupController extends GlobalController
{
    private $store;

    public function __construct(){
        parent::__construct();

        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();
    }
    
    public function index(){
        return view('fb-group-finder.index');
    }

    public function create(Request $request){
        if($request->isMethod('POST')){

        }

        return view('fb-group-finder.create');
    }

    public function edit(Request $request, $subdomain, $id){
        if($request->isMethod('POST')){

        }

        return view('fb-group-finder.edit');
    }

    public function delete(Request $request){

    }
}
