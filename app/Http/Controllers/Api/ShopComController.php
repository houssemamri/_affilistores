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

class ShopComController extends Controller
{
    private $store;
    private $settings;

    public function __construct(){
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();

        // set affiliate settings for shopcom
        $shopcom = AffiliateSetting::where('store_id', $this->store->id)->where('name', 'shopcom')->first();
        $this->settings = isset($shopcom->settings) ? ($shopcom->settings) : null;
    }

    public function search(Request $request){
        if($this->checkSettings())
            return [ 'error' => 'Please setup your Shop.com API settings under Affiliate settings'];

        $settings = json_decode($this->settings);
        $items = [];

        $query = [
            'apikey' => $settings->api_key,
            'publisherID' =>  $settings->publisher_id,
            'locale' => 'en_US',
            'perPage' => '1000',
            'term' => $request->keyword,
            'categoryId' => $request->category
        ];


        for ($page = 1; $page <= 1000; $page += 50) {
            $query['start'] = $page;
            
            $response = $this->doRequest($query);

            if(!is_array($response)){
                $response = json_decode($response);

                foreach ($response->products as $product) {
                    array_push($items, $product);
                }
    
                usleep(500000);
            }
        }

        return Response::json($items);
    }

    public function doRequest($query){
        $client = new Client();

        try {
            $response = $client->request(
                'GET', 'https://api.shop.com/AffiliatePublisherNetwork/v1/products',
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
        $settings = json_decode($this->settings);

        if(isset($settings)){
            if($settings->api_key == "" || $settings->publisher_id = "")
                return true;
            else
                return false;
        }else{
            return true;
        }
    }
}
