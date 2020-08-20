<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Response;
use Session;
use SimpleXMLElement;
use Route;
use App\Store;
use App\Product;
use App\AffiliateSetting;

class AliExpressController extends Controller
{
    private $store;
    private $settings;

    public function __construct(){
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();

        // set affiliate settings for walmart
        $aliexpress = AffiliateSetting::where('store_id', $this->store->id)->where('name', 'aliexpress')->first();
        $this->settings = isset($aliexpress->settings) ? json_decode($aliexpress->settings) : null;
    }

    public function search(Request $request){
        if($this->checkSettings())
            return [ 'error' => 'Please setup your AliExpress API settings under Affiliate settings'];

        $items = [];
        
        $query = [
            'user_api_key' =>  $this->settings->key,
            'user_hash' => $this->settings->deep_link_hash,
            "api_version" =>  "2"
        ];

        $query['requests'] = [
            'response' => [
                "action" =>  "search",
                "query" =>  $request->keyword,
                "category" => $request->category,
                'orders_count' => 'orders',
                'order_direction' => 'desc',
                "limit" =>  "1000"
            ]
        ];

        $response = json_decode($this->doRequest($query))->results->response->offers;

        return Response::json($response);
    }

    public function doRequest($query){
        $client = new Client();

        try {
            $response = $client->request(
                'POST', 'http://api.epn.bz/json',
                ['json' => $query]
            );

            return $response->getBody()->getContents();
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    public function checkSettings(){
        $settings = $this->settings;

        if(isset($settings)){
            if($settings->key == "" || $settings->deep_link_hash == "")
                return true;
            else
                return false;
        }else{
            return true;
        }
    }
}
