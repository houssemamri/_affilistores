<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Response;
use Session;
use SimpleXMLElement;
use GuzzleHttp\Client;
use Alaouy\Youtube\Facades\Youtube;
use Thujohn\Twitter\Facades\Twitter;
use Carbon\Carbon;
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

class AmazonGetProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:amazon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get products fom Amazon';

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
    public function defaultSettings(){
        return (object) [
            'market_place' => env('AMAZON_MARKET_PLACE'),
            'associate_tag' => env('AMAZON_ASSOCIATE_TAG'),
            'access_key_id' => env('AMAZON_ACCESS_KEY'),
            'secret_access_key' => env('AMAZON_SECRET_KEY'),
            'use_own_keys' => 'false'
        ];
    }

    public function getSettings($affiliateSettings){
        $settings = json_decode($affiliateSettings);
        $settings = $settings->use_own_keys == 'true' ? $settings : $this->defaultSettings();

        return $settings;
    }

    public function amazonAffiliateLink($store, $link){
        $settings = json_decode($store->affiliateSettings->where('name', 'amazon')->first()->settings);

        if(isset($settings->use_own_keys) && $settings->use_own_keys == 'false'){
            $affiliateLink = str_replace(env('AMAZON_ACCESS_KEY'), $settings->access_key_id, $link);
            $affiliateLink = str_replace(env('AMAZON_ASSOCIATE_TAG'), $settings->associate_tag, $affiliateLink);
        }else{
            $affiliateLink = $link;
        }

        return $affiliateLink;
    }

    public function handle()
    {
        $automations = Automation::whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('end_date', '>=', date('Y-m-d'))
            ->where('source', 'amazon')
            ->get();

        foreach ($automations as $automation) {
            if(!isset($automation->store)) continue;

            $affiliateSettings = $automation->store->affiliateSettings->where('name', 'amazon')->first();

            if($this->validCredentials($affiliateSettings)){
                $settings = $this->getSettings($affiliateSettings->settings);

                $product_data = json_decode($automation->product_data);
                //search functionality
                $response = $this->search($automation, $settings);
                //validate each products if already exist
                $products = $this->validateProducts($response, $automation->store, $automation->number_daily_post, $product_data, $settings);
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
            if($setting == ""){
                return false;
                break;
            }
        }

        return true;
    }

    public function generateSignature($query, $secret_key, $settings)
    {
      ksort($query);
  
      $sign = http_build_query($query);
  
      $request_method = 'GET';
      $base_url = $settings->market_place;
      $endpoint = '/onca/xml';
  
      $string_to_sign = "{$request_method}\n{$base_url}\n{$endpoint}\n{$sign}";
      $signature = base64_encode(
          hash_hmac("sha256", $string_to_sign, $secret_key, true)
      );

      return $signature;
    }

    public function setQuery($settings){
        $access_key = $settings->access_key_id;
        $secret_key = $settings->secret_access_key;
        $associate_tag = $settings->associate_tag;

        $common_params = [
            'Service' => 'AWSECommerceService',
            'Operation' => 'ItemSearch',
            'ResponseGroup' => 'Images,ItemAttributes,ItemIds,Reviews,Offers',
            'AssociateTag' => $associate_tag,
            'AWSAccessKeyId' => $access_key,
            'Availability' => 'Available'
        ];

        $query = [
            'secret_key' => $secret_key,
            'common_params' => $common_params
        ];

        return $query;
    }

    public function doRequest($query, $settings)
    {
        $setQuery = $this->setQuery($settings);
        $secret_key = $setQuery['secret_key'];
        $common_params = $setQuery['common_params'];
        $timestamp = date('c');
        $query['Timestamp'] = $timestamp;
        $query = array_merge($common_params, $query);

        $query['Signature'] = $this->generateSignature($query, $secret_key, $settings);
        $client = new Client();

        try {
            $response = $client->request(
                'GET', 'http://'. $settings->market_place .'/onca/xml', 
                ['query' => $query]
            );
            
            $contents = new SimpleXMLElement($response->getBody()->getContents());
            $contents = json_encode($contents);
            $contents = json_decode($contents, TRUE);
            
            return (object) $contents;
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }
  
    public function search($automation, $settings)
    {
        $searchIndex = $automation->category;
        $keyword = $automation->keyword;
        $pages = ['1', '2', '3', '4', '5'];
        $products = [];

        $query = [
            'Keywords' => isset($keyword) ? urlencode($keyword) : '*',
            'SearchIndex' => $searchIndex
        ];

        foreach ($pages as $page) {
            $query['ItemPage'] = $page;
            $responses = $this->doRequest($query, $settings);
            $items = isset($responses->Items['Item']) ? $responses->Items['Item'] : [];
            foreach ($items as $item) {
                array_push($products, $item);
            }
        }

        return $products;
    }

    public function validateProducts($items, $store, $limit, $product_data, $settings){
        $products = [];
        foreach ($items as $item) {
            $item = (object) $item;

            if(count($products) == $limit)
                break;

            if(!isset($item->ASIN)) continue;
            
            $is_already_existing = Product::where('reference_id', trim($item->ASIN))->where('store_id', $store->id)->count();

            if($is_already_existing == 0 && isset($item->ItemAttributes['Title'])){
                $product_details = [
                    'reference_id' => $item->ASIN,
                    'name' => $item->ItemAttributes['Title'],
                    'description' => $this->getItemDescription($item),
                    'permalink' => $this->clean($item->ItemAttributes['Title']),
                    'details_link' => $item->DetailPageURL,
                    'image' => isset($item->ImageSets) && isset($item->ImageSets['ImageSet']) ? $this->getImage($item, $item->ImageSets['ImageSet']) : asset('img/uploads/category-default.jpg'),
                    'price' => (double)trim(preg_replace('/[^0-9\.]/', '', $this->getPrice($item))),
                    'currency' => trim($this->getCurrency($item, $settings)),
                    'source' => 'amazon',
                    'published_date' => $product_data->published_date,
                    'status' => $product_data->status,
                    'images' => isset($item->ImageSets) && isset($item->ImageSets['ImageSet']) ? $this->getImages($item->ImageSets['ImageSet']) : []
                ];

                array_push($products, $product_details);
            }
        }

        return $products;
    }

    public function getItemDescription($item){
        if(isset($item->ItemAttributes['Feature'])){
            if(is_array($item->ItemAttributes['Feature'])){
                return implode(",", $item->ItemAttributes['Feature']);
            }else{
                return $item->ItemAttributes['Feature'];
            }
        }else{
            return '';
        }
    }
    
    public function getImage($item, $imageSets){
        if(isset($imageSets[0])){
            $images = [];
            foreach ($imageSets as $imageSet) {
                if($imageSet['@attributes']['Category'] == 'primary'){
                    array_push($images, $imageSet);
                }
            }

            if(count($images) >  0)
                return $imageSets[0]['LargeImage']['URL'];
            else{
                if(isset($item->LargeImage)){
                    return $item->LargeImage->URL;
                }elseif(isset($item->MediumImage)){
                    return $item->MediumImage->URL;
                }elseif(isset($item->SmallImage)){
                    return $item->SmallImage->URL;
                }else{
                    return asset('img/uploads/category-default.jpg');
                }
            }
        }else{
            if(isset($item->LargeImage->URL)){
                return $item->LargeImage->URL;
            }elseif(isset($item->MediumImage->URL)){
                return $item->MediumImage->URL;
            }elseif(isset($item->SmallImage->URL)){
                return $item->SmallImage->URL;
            }else{
                return asset('img/uploads/category-default.jpg');
            }
        }
    }

    public function getImages($imageSets){
        $images = [];

        if(isset($imageSets[0])){
            foreach ($imageSets as $imageSet) {
                if($imageSet['@attributes']['Category'] == 'variant'){
                    if(isset($imageSet['LargeImage']))
                        array_push($images, $imageSet['LargeImage']['URL']);
                }
            }
        }

        return $images;
    }

    public function getPrice($product){
        // if($product->OfferSummary){
        //     if(isset($product->OfferSummary['LowestNewPrice'])){
        //         return $product->OfferSummary['LowestNewPrice']['FormattedPrice'];
        //     }else if(isset($product->OfferSummary['LowestUsedPrice'])){
        //         return $product->OfferSummary['LowestUsedPrice']['FormattedPrice'];
        //     }else{
        //         return '<a href="' . $product->DetailPageURL . '">Check Price</a>';
        //     }
        // }
        if(isset($product->Offers) && isset($product->Offers['Offer']) && isset($product->Offers['Offer']['OfferListing'])){
            return $product->Offers['Offer']['OfferListing']['Price']['FormattedPrice'];
        }else if(isset($product->ItemAttributes['ListPrice'])){
            return $product->ItemAttributes['ListPrice']['FormattedPrice'] ;
        }else{
            return '<a href="' . $product->DetailPageURL . '">Check Price</a>';
        }
    }

    public function getCurrency($product, $settings){
        $currency = $this->getDefaultCurrency($settings->market_place);

        if(isset($product->Offers) && isset($product->Offers['Offer']) && isset($product->Offers['Offer']['OfferListing']) && isset($product->Offers['Offer']['OfferListing']['Price']) && isset($product->Offers['Offer']['OfferListing']['Price']['CurrencyCode'])){
            $currency = $product->Offers['Offer']['OfferListing']['Price']['CurrencyCode'];
        }else if(isset($product->ItemAttributes['ListPrice'])){
            $currency = $product->ItemAttributes['ListPrice']['CurrencyCode'];
        }

        return $currency;
    }

    public function getDefaultCurrency($marketPlace){
        $marketPlaces =  [
            'webservices.amazon.com.au' => 'AUD',
            'webservices.amazon.com.br' => 'BRL',
            'webservices.amazon.ca' => 'CAD',
            'webservices.amazon.cn' => 'CNY',
            'webservices.amazon.fr' => 'FRF',
            'webservices.amazon.de' => 'EUR',
            'webservices.amazon.in' => 'INR',
            'webservices.amazon.it' => 'EUR',
            'webservices.amazon.co.jp' => 'JPY',
            'webservices.amazon.com.mx' => 'MXN',
            'webservices.amazon.es' => 'EUR',
            'webservices.amazon.com.tr' => 'TRY',
            'webservices.amazon.co.uk' => 'GBP',
            'webservices.amazon.com' => 'USD',
        ];

        return $marketPlaces[$marketPlace];
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
            $affiliateLink = $this->amazonAffiliateLink($store, $item['details_link']);

            $product = Product::create([
                'store_id' => $automation->store_id,
                'reference_id' => $item['reference_id'],
                'name' => $item['name'],
                'description' => $item['description'],
                'permalink' => $item['permalink'],
                'details_link' => $item['details_link'],
                'image' => $item['image'],
                'price' => $item['price'],
                'currency' => $item['currency'],
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
