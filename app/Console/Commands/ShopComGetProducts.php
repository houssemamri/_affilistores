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

class ShopComGetProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:shopcom';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get products fom Shop.com';

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
            ->where('source', 'shopcom')
            ->get();

        foreach ($automations as $automation) {
            if(!isset($automation->store)) continue;

            $affiliateSettings = AffiliateSetting::where('store_id', $automation->store->id)->where('name', 'shopcom')->first();

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
            'apikey' => $settings->api_key,
            'publisherID' =>  $settings->publisher_id,
            'locale' => 'en_US',
            'perPage' => '50',
            'term' => $automation->keyword,
            'categoryId' => $this->getCategory($automation->category)
        ];


        for ($page = 1; $page <= 150; $page += 50) {
            $query['start'] = $page;
            $response = ($this->doRequest($query));

            if(!is_array($response)){
                $response = json_decode($this->doRequest($query));

                foreach ($response->products as $product) {
                    array_push($items, $product);
                }
            }
            
            usleep(500000);
        }

        return $items;
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

    public function getCategory($category){
        $shopcom = [
            'All' => '',
            "Baby" => "1-32804",
            "Baby" => "1-32811",
            "Books" => "1-32836",
            "Beauty" => "1-32867",
            "Business" => "1-32820",
            "Cameras" => "1-32837",
            "Clothes" => "1-32838",
            "Computers" => "1-32862",
            "Collectibles" => "1-32839",
            "Crafts" => "1-32835",
            "Electronics" => "1-32863",
            "Food and Drink" => "1-32806",
            "Garden" => "1-32808",
            "Health & Nutrition" => "1-32841",
            "Home Store" => "1-32842",
            "Jewelry" => "1-32800",
            "Movies" => "1-32819",
            "Music" => "1-32812",
            "Party Supplies" => "1-32840",
            "Pet Supplies" => "1-32809",
            "Posters" => "1-32807",
            "Shoes" => "1-32805",
            "Software" => "1-32864",
            "Sports and Fitness" => "1-32844",
            "Sports Fan Shop" => "1-32877",
            "Travel" => "1-32813",
            "Tools" => "1-32843",
            "Toys" => "1-32810",
            "Video Games" => "1-32866",
        ];


        return $shopcom[$category];
    }

    public function validateProducts($items, $store, $limit, $product_data, $settings){
        $products = [];

        foreach ($items as $item) {
            $image = $this->getImage($item);

            if($image == "")
                continue;

            if(count($products) == $limit)
                break;
            
            $is_already_existing = Product::where('reference_id', trim($item->id))->where('store_id', $store->id)->count();

            if($is_already_existing == 0){
                $price = isset($item->minimumPrice) ? $item->minimumPrice : $item->maximumPrice;

                $product_details = [
                    'reference_id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'permalink' => $this->clean($item->name),
                    'details_link' => $item->referralUrl,
                    'image' => $image,
                    'price' => (double)trim(str_replace('$', '', $price)),
                    'source' => 'shopcom',
                    'published_date' => $product_data->published_date,
                    'status' => $product_data->status,
                    'images' => []
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

        if(isset($item->imageUrl)){
            $image = $item->imageUrl;
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
            $this->addProductVideo($product->id, $product->name, $store);
            $this->addProductSeoSetting($product->id);
            $this->addProductTweets($product->id, $product->name, $store);
            $this->addProductHits($product->id, $automation->store_id);
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

            if(isset($video)){
                for ($i=0; $i < 2; $i++) {
                    ProductVideo::create([
                        'product_id' => $product_id,
                        'video' => isset($video[$i]) ? $video[$i] : '',
                        'status' => isset($video[$i]) ? 1 : 0
                    ]);
                }
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
