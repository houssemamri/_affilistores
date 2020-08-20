<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use SimpleXMLElement;
use GuzzleHttp\Client;
use Alaouy\Youtube\Facades\Youtube;
use Thujohn\Twitter\Facades\Twitter;
use Purifier;
use App\Automation;
use App\Store;
use App\AffiliateSetting;
use App\Product;
use App\ProductCategory;
use App\ProductTag;
use App\ProductImage;
use App\ProductVideo;
use App\ProductSeoSetting;
use App\ProductTweet;
use App\Category;
use App\Tag;
use App\ProductHit;
use App\AccessFeature;
use App\YoutubeKey;

class EbayGetProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:ebay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get products fom Ebay';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $automations = Automation::whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('end_date', '>=', date('Y-m-d'))
            ->where('source', 'ebay')
            ->get();

        foreach ($automations as $automation) {
            if(!isset($automation->store)) continue;
            
            $affiliateSettings = $automation->store->affiliateSettings->where('name', 'ebay')->first();
    
            if($this->validCredentials($affiliateSettings)){
                $product_data = json_decode($automation->product_data);
                //search functionality
                $response = $this->search($automation, json_decode($affiliateSettings->settings));
                //validate each products if already exist
                $products = $this->validateProducts($response, $automation->store, $automation->number_daily_post, $product_data, json_decode($affiliateSettings->settings));
                //insert base on the daily post
                $inserted = $this->insertNewProducts($automation, $products, $product_data, $automation->store);
            }
        }
    }

    public function checkFeatures($store, $name){
        $features = AccessFeature::where('membership_id', $store->user->memberDetail->membership->id)->get();

        foreach ($features as $feature) {
            if(strtolower($feature->features->name) == strtolower($name)){
                return true;
            }
        }

        return false;
    }

    public function validCredentials($affiliateSettings){
        foreach (json_decode($affiliateSettings->settings) as $setting) {
            if($setting == 'application_id' && $setting == ""){
                return false;
                break;
            }
        }

        return true;
    }

    public function search($automation, $settings){
        $categoryId = $this->getCategory($automation->category);
        $keyword = $automation->keyword;
        $client = new Client();

        $query = $this->generatequery($keyword, $categoryId, $settings);
        
        try {
            $response = $client->request(
                'GET', 'http://svcs.ebay.com/services/search/FindingService/v1', 
                ['query' => $query]
            );

            $contents = json_decode($response->getBody()->getContents());
            if($contents->findItemsAdvancedResponse[0]->ack[0] == 'Success')
                return $contents->findItemsAdvancedResponse[0]->searchResult[0]->item;
            else
                return [];    
        } catch (\Exception $e) {
           return [];    
        }
    }

    public function getCategory($category){
        $ebay = [
            'All' => '1',
            'Art' => '550',
            'Baby' => '2984',
            'Books, Comics & Magazines' => '267',
            'Business, Office & Industrial' => '12576',
            'Cameras & Photography' => '625',
            'Cars, Motorcycles & Vehicles' => '6001',
            'Clothes, Shoes & Accessories' => '11450',
            'Coins' => '11116',
            'Computers/Tablets & Networking' => '58058',
            'Consumer Electronics' => '293',
            'Crafts' => '14339',
            'Dolls & Bears' => '237',
            'DVDs, Films & TV' => '11232',
            'Events Tickets' => '1305',
            'Mobile Phones & Communication' => '15032',
            'Health & Beauty' => '26395',
            'Garden & Patio' => '159912',
            'Jewelry & Watches' => '281',
            'Music' => '11233',
            'Musical Instruments' => '619',
            'Pet Supplies' => '1281',
            'Pottery, Porcelain & Glass' => '870',
            'Property' => '10542',
            'Sporting Goods' => '888',
            'Sound & Vision' => '293',
            'Stamps' => '260',
            'Sports Memorabilia' => '64482',
            'Toys & Hobbies' => '220',
            'Video Games & Consoles' => '1249',
            'Decals, Stickers & Vinyl Art' => '159889',
        ];

        return $ebay[$category];
    }

    public function generatequery($keywords, $categoryId, $settings){
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
            'paginationInput.entriesPerPage' => '50',
            'paginationInput.pageNumber' => '1',
        ];

        return http_build_query($query);
    }

    public function ebayGetItemDetails($itemId, $settings){
        $client = new Client();
        $details = [];

        $query = [
            'callname' => 'GetSingleItem',
            'responseencoding' => 'JSON',
            'appid' => $settings->application_id,
            'siteid' => '0',
            'version' => '967',
            'ItemID' => $itemId,
            'IncludeSelector' => 'Description'
        ];

        try {
            $response = $client->request(
                'GET', 'http://open.api.ebay.com/shopping', 
                ['query' => http_build_query($query)]
            );

            $content = json_decode($response->getBody()->getContents());
            // $details['description'] = Purifier::clean($content->Item->Description);
            $details['description'] = ($content->Item->Description);
            $details['images'] = $content->Item->PictureURL;
            
            return $details;
        } catch (Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    public function validateProducts($items, $store, $limit, $product_data, $settings){
        $products = [];
        foreach ($items as $item) {
            
            if(count($products) == $limit)
                break;
            
            $item->itemId = $item->itemId[0];
            $is_already_existing = Product::where('reference_id', trim($item->itemId))->where('store_id', $store->id)->count();

            if($is_already_existing == 0){
                $details = $this->ebayGetItemDetails($item->itemId, $settings);
                $price = ($item->sellingStatus[0]->convertedCurrentPrice[0]) ? $item->sellingStatus[0]->convertedCurrentPrice[0]->__value__ : 0.00;

                $product_details = [
                    'reference_id' => $item->itemId,
                    'name' => $item->title[0],
                    'description' => $details['description'],
                    'permalink' => $this->clean($item->title[0]),
                    'details_link' => $item->viewItemURL[0],
                    'image' => isset($item->galleryURL) ? $item->galleryURL[0] : 'http://thumbs1.ebaystatic.com/pict/04040.jpg',
                    'price' => (double)trim(str_replace('$', '', $price)),
                    'source' => 'ebay',
                    'published_date' => $product_data->published_date,
                    'status' => $product_data->status,
                    'images' => $details['images']
                ];

                array_push($products, $product_details);
            }
        }

        return $products;
    }
    
    public function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = trim(preg_replace('/-+/', '-', $string), '-');
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
		$string = strtolower($string); // Convert to lowercase
 
		return $string;
    }
    
    public function insertNewProducts($automation, $items, $product_data, $store){
        foreach ($items as $item) {
            $product = Product::create([
                'store_id' => $automation->store_id,
                'reference_id' => $item['reference_id'],
                'name' => $item['name'],
                'description' => $item['description'],
                'permalink' => $item['permalink'],
                'details_link' => $item['details_link'],
                'image' => $item['image'],
                'price' => $item['price'],
                'source' => $item['source'],
                'published_date' => $item['published_date'],
                'status' => $item['status'],
            ]);
            
            $this->addProductCategory(explode(',', $product_data->categories), $product->id);
            $this->addProductTag(explode(',', $product_data->tags), $product->id, $automation->store_id);
            $this->addProductImage($item['images'], $product->id);
            $this->addProductSeoSetting($product->id);
            $this->addProductTweets($product->id, $product->name, $store);
            $this->addProductHits($product->id, $automation->store_id);
            $this->addProductVideo($product->id, $product->name, $store);
        }
    }

    public function addProductCategory($categories, $product_id){
        foreach ($categories as $category) {
            ProductCategory::create([
                'category_id' => $category,
                'product_id' => $product_id,
            ]);
        }
    }

    public function addProductTag($tags, $product_id, $store_id){
        // $productTags = ProductTag::where('product_id', $product_id)->delete();

        foreach ($tags as $tag) {
            ProductTag::create([
                'tag_id' => $tag,
                'product_id' => $product_id,
            ]);
        }
    }

    public function addProductImage($images, $product_id){
        foreach ($images as $image) {
            ProductImage::create([
                'product_id' => $product_id,
                'image' => $image,
                'type' => 'default'
            ]);
        }
    }

    public function addProductVideo($product_id, $keyword, $store){
        if($this->checkFeatures($store, 'YouTube Videos')){
            $video = $this->youtubeSearch($keyword);

            for ($i=0; $i < 2; $i++) {
                ProductVideo::create([
                    'product_id' => $product_id,
                    'video' => isset($video[$i]) ? $video[$i] : '',
                    'status' => isset($video[$i]) ? 1 : 0
                ]);
            }
        }
    }

    public function addProductTweets($product_id, $keyword, $store){
        if($this->checkFeatures($store, 'Related Tweets')){
            $tweets = $this->twitterSearch($keyword);

            foreach($tweets as $tweet){
                ProductTweet::create([
                    'product_id' => $product_id,
                    'tweet_id' => $tweet['tweet_id'],
                    'user' => $tweet['user'],
                    'content' => $tweet['content'],
                    'user_profile_img' => $tweet['user_profile_img'],
                ]);
            }  
        }
    }

    public function addProductSeoSetting($product_id){
        ProductSeoSetting::create([
            'product_id' => $product_id,
        ]);
    }

    public function addProductHits($product_id, $store_id){
        ProductHit::create([
            'store_id' =>$store_id,
            'product_id' => $product_id,
        ]);
    }

    public function youtubeSearch($keyword){
        $youtubeKeys = YoutubeKey::all();

        try{
            $videoLinks = [];
            $params = [
                'q'             => $keyword,
                'type'          => 'video',
                'part'          => 'id, snippet',
                'maxResults'    => 5
            ];

            $search = null;

            foreach ($youtubeKeys as $youtubeKey) {
                Youtube::setApiKey($youtubeKey->api_key);
                $search = $this->getVideoSearch($params);

                if(isset($search)) break;
            }

            if(isset($search)){
                $results = $search['results'] == false ? $search['results'] : rsort($search['results']);

                if($results){
                    foreach ($search['results'] as $key => $result) {
                        if ($key == 2) break;
            
                        $url = 'https://www.youtube.com/watch?v=' . $result->id->videoId;
                        array_push($videoLinks, $url);
                    }
                }
            }
            
            return $videoLinks;
        } catch(\Exception $e){
            return [];
        }
    }

    public function getVideoSearch($params){
        try{
            return Youtube::searchAdvanced($params, true);
        }catch(\Exception $e){
            return null;
        }
    }

    public function twitterSearch($keyword){
        $params = [ 'q' => $keyword ];
        $tweets = [];

        try{
            $results = Twitter::getSearch($params);

            foreach($results->statuses as $key => $result){
                if ($key == 2) break;
    
                $tweets[$key] = [
                    'tweet_id' => $result->id,
                    'content' => $result->text,
                    'user_profile_img' => $result->user->profile_image_url,
                    'user' => $result->user->name
                ];
            }
            
            return $tweets;
        }catch(\Exception $e){
            return $tweets;
        }
    }
}
