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

class CjComController extends Controller
{
    private $store;
    private $settings;

    public function __construct(){
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();

        // set affiliate settings for ebay
        $cjcom = AffiliateSetting::where('store_id', $this->store->id)->where('name', 'cjcom')->first();
        $this->settings = json_decode($cjcom->settings);
    }

    public function search(Request $request)
    {
        $client = new Client();
        
        $query = [
            'website-id' => $this->settings->website_id,
            'keywords' => $request->keyword,
            'sort-by' => 'sale-price',
            'sort-order' => 'desc',
            'records-per-page' => '1000'
        ];

        try {
            $response = $client->request(
                'GET', 'https://product-search.api.cj.com/v2/product-search', 
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->settings->api_key    
                    ],
                    'query' => $query
                ]
            );
            
            $contents = new SimpleXMLElement($response->getBody()->getContents());

            if(isset($contents->products->product)){
                return Response::json($contents);
            }else{
                return [];
            }
        } catch (\Exception $e) {
            return [];
        }
    }
}
