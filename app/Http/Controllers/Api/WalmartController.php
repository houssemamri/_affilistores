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

class WalmartController extends Controller
{
    private $store;
    private $settings;

    public function __construct(){
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();

        // set affiliate settings for walmart
        $walmart = AffiliateSetting::where('store_id', $this->store->id)->where('name', 'walmart')->first();
        $this->settings = isset($walmart->settings) ? json_decode($walmart->settings) : null;
    }

    public function search(Request $request){
        if($this->checkSettings())
            return [ 'error' => 'Please setup your Walmart API settings under Affiliate settings'];

        $items = [];
        
        $query = [
            'apiKey' => isset($this->settings) ? $this->settings->key : '',
            'query' => $request->keyword,
            'sort' => 'bestseller',
            'numItems' => 25,
            'format' => 'json'
        ];

        if(isset($request->category) && $request->category !== '')
            $query['categoryId'] = $request->category;

        for ($page = 1; $page <= 1000; $page += 25) {
            $query['start'] = $page;
            $response = $this->doRequest($query);

            $products = json_decode($response)->items;

            foreach ($products as $product) {
                array_push($items, $product);
            }

            usleep(500000);
        }

        return Response::json($items);
    }

    public function doRequest($query){
        $client = new Client();

        try {
            $response = $client->request(
                'GET', 'http://api.walmartlabs.com/v1/search',
                ['query' => $query]
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
            if($settings->key == "")
                return true;
            else
                return false;
        }else{
            return true;
        }
    }
}
