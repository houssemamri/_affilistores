<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use Route;
use Session;
use Auth;
use Crypt;
use App\Product;
use App\Store;
use App\ProductCountdown;

class CountdownController extends GlobalController
{
    private $store;

    public function __construct(){
        parent::__construct();
        
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();
    }

    public function index(){
        $countdowns = ProductCountdown::where('store_id', $this->store->id)->get();

        return view('countdowns.index', compact('countdowns'));
    }

    public function create(Request $request){
        $products = Product::select('id', 'name')->where('store_id', $this->store->id)->orderBy('name', 'ASC')->get();

        if($request->isMethod('POST')){
            $this->validate($request, [
                'product' => 'required',
                'name' => 'required',
                'description' => 'required',
                'access_link' => 'required',
                'countdown_date' => 'required'
            ]);
            $existing = ProductCountdown::where('product_id', $request->product)->first();

            if(isset($existing) && $existing->countdown_date >= date('Y-m-d')){
                Session::flash('error', 'Product already has countdown.');
                return redirect()->back()->withInput();
            }

            $data = [
                'background_color' => $request->background_color,
                'color' => $request->text_color
            ];

            ProductCountdown::create([
                'store_id' => $this->store->id, 
                'product_id' => $request->product, 
                'name' => $request->name, 
                'description' => $request->description, 
                'access_link' => $request->access_link, 
                'countdown_date' => $request->countdown_date, 
                'settings' => json_encode($data)
            ]);

            Session::flash('success', 'Product countdown timer successfully saved.');
             return redirect()->route('countdowns.index', $this->store->subdomain);
        }

        return view('countdowns.create', compact('products'));
    }

    public function edit(Request $request, $subdomain, $id){
        $countdown = ProductCountdown::where('id', Crypt::decrypt($id))->first();
         
        if(isset($countdown)){
            $products = Product::select('id', 'name')->where('store_id', $this->store->id)->orderBy('name', 'ASC')->get();

            if($request->isMethod('POST')){
                $this->validate($request, [
                    'product' => 'required',
                    'name' => 'required',
                    'description' => 'required',
                    'access_link' => 'required',
                    'countdown_date' => 'required'
                ]);
            
                $data = [
                    'background_color' => $request->background_color,
                    'color' => $request->text_color
                ];

                $countdown->update([
                    'store_id' => $this->store->id, 
                    'product_id' => $request->product, 
                    'name' => $request->name, 
                    'description' => $request->description, 
                    'access_link' => $request->access_link, 
                    'countdown_date' => $request->countdown_date, 
                    'settings' => json_encode($data)
                ]);
    
                Session::flash('success', 'Product countdown timer successfully updated.');
                 return redirect()->route('countdowns.index', $this->store->subdomain);
            }

            return view('countdowns.edit', compact('products', 'id', 'countdown'));
        }else{
            Session::flash('error', 'Oops something went wrong! Invalid product countdown id.');
             return redirect()->route('countdowns.index', $this->store->subdomain);
        }
    }

    public function delete(Request $request){
        $countdown = ProductCountdown::where('id', Crypt::decrypt($request->countdown_id))->first();
         
        if(isset($countdown)){
            $countdown->delete();
            Session::flash('success', 'Product countdown timer successfully deleted.');
        }else{
            Session::flash('error', 'Oops something went wrong! Invalid product countdown id.');
        }

         return redirect()->route('countdowns.index', $this->store->subdomain);
    }
}
