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

class EbayController extends Controller
{
    private $store;
    private $settings;

    public function __construct(){
        //get current store
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();

        //set affiliate settings for ebay
        $ebay = AffiliateSetting::where('store_id', $this->store->id)->where('name', 'ebay')->first();
        $this->settings = json_decode($ebay->settings);
    }
    
    public function search(Request $request){
        if($this->checkSettings())
            return [ 'error' => 'Please setup your ebay settings under Affiliate settings'];

        $categoryId = $request['searchIndex'];
        $keyword = $request['keyword'];
        $query = $this->generatequery($keyword, $categoryId);
        $pages = ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];
        $items = [];

        foreach ($pages as $page) {
            $query['paginationInput.pageNumber'] = $page;
            $responses = $this->doRequest($query, $page);

            if(!isset($responses->{'@count'}) ||$responses->{'@count'} == 0)
                break;
               
            foreach ($responses->item as $response) {
                array_push($items, $response);
            }
        }

        return $items;
    }

    public function doRequest($query){
        $client = new Client();

        try {

            $response = $client->request(
                'GET', 'http://svcs.ebay.com/services/search/FindingService/v1', 
                ['query' => http_build_query($query)]
            );

            $contents = json_decode($response->getBody()->getContents());
            
            if($contents->findItemsAdvancedResponse[0]->ack[0] == 'Success')
                return ($contents->findItemsAdvancedResponse[0]->searchResult[0]);
            else
                return [];
        } catch (Exception $e) {
            return [];
        }
    }

    public function generatequery($keywords, $categoryId){
       $settings = $this->settings;

        $query = [
            'OPERATION-NAME' => 'findItemsAdvanced',
            'SERVICE-VERSION' => '1.0.0',
            'GLOBAL-ID' => 'EBAY-US',
            'SECURITY-APPNAME' => $settings->application_id,
            'RESPONSE-DATA-FORMAT' => 'JSON',
            'REST-PAYLOAD' => '',
            'keywords' => $keywords,
            'categoryId' => $categoryId,
            // 'affiliate.networkId' => isset($settings->network_id) ? $settings->network_id : '',
            'affiliate.networkId' => '9',
            'affiliate.customId' => isset($settings->custom_id) ? $settings->custom_id : '',
            'affiliate.trackingId' => isset($settings->tracking_id) ? $settings->tracking_id : '',
            'itemFilter.name' => 'BestOfferOnly',
            'itemFilter.value' => 'true',
            'paginationInput.entriesPerPage' => '100',
            // 'paginationInput.pageNumber' => '1',
        ];

        return $query;
    }

    public function getItemDetails(Request $request){
        $itemId = $request['itemId'];
        $settings = $this->settings;
        $client = new Client();

        $query = [
            'callname' => 'GetSingleItem',
            'responseencoding' => 'JSON',
            'appid' => $settings->application_id,
            'siteid' => '0',
            'version' => '967',
            'ItemID' => $itemId,
            'IncludeSelector' => 'Description,ItemSpecifics'
        ];

        try {
            $response = $client->request(
                'GET', 'http://open.api.ebay.com/shopping', 
                ['query' => http_build_query($query)]
            );
            
            // $results = (json_decode($response->getBody()->getContents())->Item);
            
            return $response->getBody()->getContents();
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    public function checkSettings(){
        $settings = $this->settings;
        
        if($settings->application_id == "")
            return true;
        else 
            return false;
    }
}
