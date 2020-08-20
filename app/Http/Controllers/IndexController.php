<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Crypt;
use App\Setup;
use App\Store;
use App\Page;

class IndexController extends Controller
{
    public function index(){
        $site = $this->site();
        return view('index', compact('site'));
    }

    public function storeClose($id){
        $site = $this->site();
        $store = Store::where('id', Crypt::decrypt($id))->first();

        return view('store-close', compact('store', 'site'));
    }

    public function site(){
        $setups = Setup::all();
        $site = [];
        foreach ($setups as $setup) {
            $site[$setup->key] = $setup->value;
        }

        return $site;
    }

    public function privacy(Request $request){
        $privacy = Page::where('slug', 'terms-of-service')->first();

        if(isset($privacy)){
            $site = $this->site();

            return view('privacy', compact('privacy', 'site'));
        }else{
            return redirect()->route('main.index');
        }
    }

    public function terms(Request $request){
        $terms = Page::where('slug', 'terms-of-service')->first();

        if(isset($terms)){
            $site = $this->site();

            return view('terms', compact('terms', 'site'));
        }else{
            return redirect()->route('main.index');
        }
    }
}
