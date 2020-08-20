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

class WalmartGetProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:walmart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get products fom Walmart';

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
            ->where('source', 'walmart')
            ->get();

        foreach ($automations as $automation) {
            if(!isset($automation->store)) continue;
           
            $affiliateSettings = $automation->store->affiliateSettings->where('name', 'walmart')->first();

            if(isset($affiliateSettings)){
                if($this->validCredentials($affiliateSettings)){
                    $product_data = json_decode($automation->product_data);
                    //search functionality
                    $response = $this->search($automation, json_decode($affiliateSettings->settings));
                    // validate each products if already exist
                    $products = $this->validateProducts($response, $automation->store, $automation->number_daily_post, $product_data, json_decode($affiliateSettings->settings));
                    // insert base on the daily post
                    $inserted = $this->insertNewProducts($automation, $products, $product_data, $automation->store);
                }
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

    public function search($automation, $settings){
        $items = [];
        
        $query = [
            'apiKey' => $settings->key,
            'query' => $automation->keyword,
            'sort' => 'bestseller',
            'numItems' => 25,
            'format' => 'json'
        ];

        if(isset($automation->category) && $automation->category !== '' && $automation->category !== 'All')
            $query['categoryId'] = $this->getCategory($automation->category);

        for ($page = 1; $page <= 100; $page += 25) {
            $query['start'] = $page;

            $response = $this->doRequest($query);
            
            $products = isset($response->items) ? $response->items : [];

            foreach ($products as $product) {
                array_push($items, $product);
            }
        }

        return $items;
    }

    public function doRequest($query){
        $client = new Client();

        try {
            $response = $client->request(
                'GET', 'http://api.walmartlabs.com/v1/search',
                ['query' => $query]
            );
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            return [];
        }

        return $query;
    }

    public function getCategory($category){
        $walmart = [
            'All' => '',
            "Arts, Crafts & Sewing" => "1334134",
            "Auto & Tires" => "91083",
            "Baby" => "5427",
            "Beauty" => "1085666",
            "Books" => "3920",
            "Cell Phones" => "1105910",
            "Clothing" => "5438",
            "Electronics" => "3944",
            "Food" => "976759",
            "Gifts & Registry" => "1094765",
            "Health" => "976760",
            "Home" => "4044",
            "Home Improvement" => "1072864",
            "Household Essentials" => "1115193",
            "Industrial & Scientific" => "6197502",
            "Jewelry" => "3891",
            "Movies & TV Shows" => "4096",
            "Music on CD or Vinyl" => "4104",
            "Musical Instruments" => "7796869",
            "Office" => "1229749",
            "Party & Occasions" => "2637",
            "Patio & Garden" => "5428",
            "Personal Care" => "1005862",
            "Pets" => "5440",
            "Photo Center" => "5426",
            "Premium Beauty" => "7924299",
            "Seasonal" => "1085632",
            "Sports & Outdoors" => "4125",
            "Toys" => "4171",
            "Video Games" => "2636",
            "Walmart for Business" => "6735581",
        ];


        return $walmart[$category];
    }

    public function validateProducts($items, $store, $limit, $product_data, $settings){
        $products = [];
        foreach ($items as $item) {
            $image = $this->getImage($item);

            if($image == "")
                continue;

            if(count($products) == $limit)
                break;
            
            $item->itemId = $item->itemId;
            $is_already_existing = Product::where('reference_id', trim($item->itemId))->where('store_id', $store->id)->count();

            if($is_already_existing == 0){
                $price = isset($item->salePrice) ? $item->salePrice : null;
                $price = !isset($price) && isset($item->msrp) ? $item->msrp : $price;
                $price = isset($price) ? $price : 0;

                $product_details = [
                    'reference_id' => $item->itemId,
                    'name' => $item->name,
                    'description' => isset($item->shortDescription) ? $item->shortDescription : '',
                    'permalink' => $this->clean($item->name),
                    'details_link' => $item->productUrl,
                    'image' => $image,
                    'price' => (double)trim($price),
                    'source' => 'walmart',
                    'published_date' => $product_data->published_date,
                    'status' => $product_data->status,
                    'images' => isset($item->imageEntities) ? $item->imageEntities : []
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
    
    public function getImage($item){
        $image = '';

        if(isset($item->largeImage)){
            $image = $item->largeImage;
        }elseif(isset($item->mediumImage)){
            $image = $item->mediumImage;
        }elseif(isset($item->thumbnailImage)){
            $image = $item->thumbnailImage;
        }

        return $image;
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
                'image' => $this->getImage($image),
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
