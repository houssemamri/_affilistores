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
use App\AccessFeature;

class AmazonController extends Controller
{
    private $store;
    private $settings;

    private $marketPlace;
    private $accessKeyId;
    private $secretAccessKey;
    private $associateTag;

    public function __construct(){
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();

        //set affiliate settings for ebay
        $amazon = AffiliateSetting::where('store_id', $this->store->id)->where('name', 'amazon')->first();
        $this->setSettings(json_decode($amazon->settings));
    }

    public function setSettings($settings){
        $settings = $settings;

        if(isset($settings->use_own_keys)){
            if($settings->use_own_keys == 'true'){
                $this->marketPlace = $settings->market_place;
                $this->accessKeyId = $settings->access_key_id;
                $this->secretAccessKey = $settings->secret_access_key;
                $this->associateTag = $settings->associate_tag;
            }else{
                $this->marketPlace = env('AMAZON_MARKET_PLACE');
                $this->accessKeyId = env('AMAZON_ACCESS_KEY');
                $this->secretAccessKey = env('AMAZON_SECRET_KEY');
                $this->associateTag = env('AMAZON_ASSOCIATE_TAG');
            }
        }else{
            $this->marketPlace = $settings->market_place;
            $this->accessKeyId = $settings->access_key_id;
            $this->secretAccessKey = $settings->secret_access_key;
            $this->associateTag = $settings->associate_tag;
        }
    }

    public function generateSignature($query, $secret_key)
    {
      ksort($query);
  
      $sign = http_build_query($query);
  
      $request_method = 'GET';
      $base_url = $this->marketPlace;
      $endpoint = '/onca/xml';
  
      $string_to_sign = "{$request_method}\n{$base_url}\n{$endpoint}\n{$sign}";
      $signature = base64_encode(
          hash_hmac("sha256", $string_to_sign, $secret_key, true)
      );

      return $signature;
    }

    public function setQuery($operation, $responseGroup = null){
        $store = Store::where('subdomain', Session::get('subdomain'))->first();

        $access_key = $this->accessKeyId;
        $secret_key = $this->secretAccessKey;
        $associate_tag = $this->associateTag;

        $common_params = [
            'Service' => 'AWSECommerceService',
            'Operation' => $operation,
            'ResponseGroup' => isset($responseGroup) ? $responseGroup : 'Images,ItemAttributes,ItemIds,Reviews,Offers',
            // 'Sort' => 'salesrank',
            'AssociateTag' => $associate_tag,
            'AWSAccessKeyId' => $access_key,
        ];

        $query = [
            'secret_key' => $secret_key,
            'common_params' => $common_params
        ];
        return $query;
    }

    public function setReviewQuery(){
        $store = Store::where('subdomain', Session::get('subdomain'))->first();

        $access_key = $this->accessKeyId;
        $secret_key = $this->secretAccessKey;
        $associate_tag = $this->associateTag;

        $common_params = [
            'Service' => 'AWSECommerceService',
            'Operation' => 'ItemSearch',
            'ResponseGroup' => 'Accessories,BrowseNodes,Images,ItemAttributes,ItemIds,Reviews,Small',
            'AssociateTag' => $associate_tag,
            'AWSAccessKeyId' => $access_key,
        ];

        $query = [
            'secret_key' => $secret_key,
            'common_params' => $common_params
        ];
        return $query;
    }
  
    public function doRequest($query)
    {
        $secret_key = $this->setQuery('ItemSearch')['secret_key'];
        $common_params = $this->setQuery('ItemSearch')['common_params'];

        $timestamp = date('c');
        $query['Timestamp'] = $timestamp;
        $query = array_merge($common_params, $query);
        $query['Signature'] = $this->generateSignature($query, $secret_key);
        $client = new Client();

        try {
            $response = $client->request(
                'GET', 'http://'. $this->marketPlace .'/onca/xml', 
                ['query' => $query]
            );
            
            $contents = new SimpleXMLElement($response->getBody()->getContents());
            
            return $contents;
        } catch (Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }
  
    public function search(Request $request)
    {
        if($this->checkSettings())
            return [ 'error' => 'Please setup your amazon settings under Affiliate settings'];

        $searchIndex = $request['searchIndex'];
        $keyword = $request['keyword'];
        $pages = $request['pageLength'];
        $responses = [];

        $query = [
            'Keywords' => isset($keyword) ? urlencode($keyword) : '*',
            'SearchIndex' => $searchIndex
        ];

        foreach ($pages as $page) {
            $query['ItemPage'] = $page;
            $response = $this->doRequest($query);
            array_push($responses, $response);
        }
        
        return Response::json($responses);
    }

    public function checkSettings(){
        if($this->accessKeyId == "" || $this->secretAccessKey == "" || $this->associateTag == "")
            return true;
        else 
            return false;
    }

    //customer reviews
    public function getReviewURL($query)
    {
        $secret_key = $this->setQuery('ItemLookup', 'Reviews')['secret_key'];
        $common_params = $this->setQuery('ItemLookup', 'Reviews')['common_params'];

        $timestamp = date('c');
        $query['Timestamp'] = $timestamp;
        $query = array_merge($common_params, $query);
        $query['Signature'] = $this->generateSignature($query, $secret_key);
        $client = new Client();

        try {
            $response = $client->request(
                'GET', 'http://'. $this->marketPlace .'/onca/xml', 
                ['query' => $query]
            );
            
            $contents = new SimpleXMLElement($response->getBody()->getContents());
            
            return $contents;
        } catch (Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    public function getRequestReviewURL($subdomain, $itemId){
        if(!$this->checkFeatures('Amazon Reviews'))
            return Response::json('');
            
        if($this->checkSettings())
            return [ 'error' => 'Please setup your amazon settings under Affiliate settings'];


        $query['IdType'] = 'ASIN';
        $query['ItemId'] = $itemId;

        $response = $this->getReviewURL($query);
        
        return Response::json($response);
    }

    public function checkFeatures($name){
        $store = $this->store;
        $features = AccessFeature::where('membership_id', $store->user->memberDetail->membership->id)->get();

        foreach ($features as $feature) {
            if(strtolower($feature->features->name) == strtolower($name)){
                return true;
            }
        }

        return false;
    }
}
