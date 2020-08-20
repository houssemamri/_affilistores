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
use App\JvzooProduct;
use App\PayDotComProduct;
use App\YoutubeKey;

class PayDotComGetProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:paydotcom';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get products from PayDotCom';

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
    public function handle(){
        $automations = Automation::whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('end_date', '>=', date('Y-m-d'))
            ->where('source', 'paydotcom')
            ->get();

        foreach ($automations as $automation) {
            if(!isset($automation->store)) continue;

            if(!$this->checkFeatures($automation->store, 'paydotcom')) continue;    
            
            $product_data = json_decode($automation->product_data);
                //search functionality
            $response = $this->search($automation);
            //validate each products if already exist
            $products = $this->validateProducts($response, $automation->store, $automation->number_daily_post, $product_data);
            //insert base on the daily post
            $inserted = $this->insertNewProducts($automation, $products, $product_data, $automation->store);
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

    public function search($automation)
    {
        $keyword = $automation->keyword;
        $items = PayDotComProduct::orWhere('name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('description', 'LIKE', '%' . $keyword . '%')->take(1000)->get();
        return $items;
    }

    public function validateProducts($items, $store, $limit, $product_data){
        $products = [];
        foreach ($items as $item) {
            if(count($products) == $limit)
                break;
            
            $is_already_existing = Product::where('reference_id', $item->reference_id)->where('store_id', $store->id)->count();

            if($is_already_existing == 0){
                $product_details = [
                    'reference_id' => $item->reference_id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'permalink' => $this->clean($item->name),
                    'details_link' => $item->request_url,
                    'image' => asset('img/uploads/category-default.jpg'),
                    'price' => 0,
                    'source' => 'paydotcom',
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
