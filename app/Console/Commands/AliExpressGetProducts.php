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

class AliExpressGetProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:aliexpress';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get products fom AliExpress';

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
            ->where('source', 'aliexpress')
            ->get();

        foreach ($automations as $automation) {
            if(!isset($automation->store)) continue;

            $affiliateSettings = $automation->store->affiliateSettings->where('name', 'aliexpress')->first();
            
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
            'user_api_key' =>  $settings->key,
            'user_hash' => $settings->deep_link_hash,
            "api_version" =>  "2"
        ];

        $query['requests'] = [
            'response' => [
                "action" =>  "search",
                "query" =>  $automation->keyword,
                "category" => $this->getCategory($automation->category),
                "limit" =>  "1000"
            ]
        ];

        return json_decode($this->doRequest($query))->results->response->offers;
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

    public function getCategory($category){
        $aliexpress = [
            'All' => '',
            "Automobiles & Motorcycles" => "34",
            "Beauty & Health" => "66",
            "Cellphones & Telecommunications" => "509",
            "Computer & Office" => "7",
            "Consumer Electronics" => "44",
            "Electronic Components & Supplies" => "502",
            "Furniture" => "1503",
            "Hair Extensions & Wigs" => "200002489",
            "Hair & Accessories" => "200165144",
            "Home Appliances" => "6",
            "Home Improvement" => "13",
            "Home & Garden" => "15",
            "Jewelry & Accessories" => "1509",
            "Lights & Lighting" => "39",
            "Luggage & Bags" => "1524",
            "Men's Clothing & Accessories" => "100003070",
            "Mother & Kids" => "1501",
            "Novelty & Special Use" => "200000875",
            "Office & School Supplies" => "21",
            "Security & Protection" => "30",
            "Shoes" => "322",
            "Sports & Entertainment" => "18",
            "Tools" => "1420",
            "Toys & Hobbies" => "26",
            "Watches" => "1511",
            "Weddings & Events" => "100003235",
            "Women's Clothing & Accessories" => "100003109",
        ];


        return $aliexpress[$category];
    }

    public function validateProducts($items, $store, $limit, $product_data, $settings){
        $products = [];
        foreach ($items as $item) {
            if(count($products) == $limit)
                break;
            
            $is_already_existing = Product::where('reference_id', trim($item->id))->where('store_id', $store->id)->count();

            if($is_already_existing == 0){
                $price = isset($item->sale_price) ? $item->sale_price : $item->price;

                $product_details = [
                    'reference_id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'permalink' => $this->clean($item->name),
                    'details_link' => $item->url,
                    'image' => $item->picture,
                    'price' => (double)trim($price),
                    'source' => 'aliexpress',
                    'published_date' => $product_data->published_date,
                    'status' => $product_data->status,
                    'images' => $item->all_images
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

        if($item->largeImage){
            $image = $item->largeImage;
        }elseif($item->mediumImage){
            $image = $item->mediumImage;
        }elseif($item->thumbnailImage){
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
