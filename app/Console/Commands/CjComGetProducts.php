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

class CjComGetProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:cjcom';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get products from Cj.com';

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
            ->where('source', 'cjcom')
            ->get();

        foreach ($automations as $automation) {
            if(!isset($automation->store)) continue;

            $affiliateSettings = $automation->store->affiliateSettings->where('name', 'cjcom')->first();
            
            if($this->validCredentials($affiliateSettings)){
                $product_data = json_decode($automation->product_data);
                //search functionality
                $response = $this->search($automation, json_decode($affiliateSettings->settings));
                //validate each products if already exist
                $products = $this->validateProducts($response, $automation->store, $automation->number_daily_post, $product_data);
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

    public function numberOfSearch($automation){
        $start_date = date_create($automation->start_date);
        $end_date = date_create($automation->end_date);
        
        return (date_diff($start_date, $end_date)->days + 1) * $automation->number_daily_post;
    }

    public function search($automation, $settings)
    {
        $keyword = $automation->keyword;
        $products = [];
        $client = new Client();

        $query = [
            'website-id' => $settings->website_id,
            'keywords' => $keyword,
            'sort-by' => 'sale-price',
            'records-per-page' => $this->numberOfSearch($automation)
        ];

        try {
            $response = $client->request(
                'GET', 'https://product-search.api.cj.com/v2/product-search', 
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $settings->api_key    
                    ],
                    'query' => $query
                ]
            );
            
            $contents = simplexml_load_string($response->getBody()->getContents(), 'SimpleXMLElement', LIBXML_NOCDATA);
            
            if(isset($contents->products->product)){
                foreach ($contents->products->product as $product) {
                    array_push($products, $product);
                }
            }
        } catch (\Exception $e) {
            return [];
        }

        return json_decode(json_encode($products, TRUE), TRUE);
    }

    public function validateProducts($items, $store, $limit, $product_data){
        $products = [];
        foreach ($items as $item) {
            $item = (object) $item;
            if(count($products) == $limit)
                break;
            
            $is_already_existing = Product::where('reference_id', trim($item->{'catalog-id'}))->where('store_id', $store->id)->count();

            if($is_already_existing == 0){
                $product_details = [
                    'reference_id' => $item->{'catalog-id'},
                    'name' => $item->name,
                    'description' => $item->description,
                    'permalink' => $this->clean($item->name),
                    'details_link' => $item->{'buy-url'},
                    'image' => isset($item->{'image-url'}) && !empty($item->{'image-url'}) ? $item->{'image-url'} : '',
                    'price' => (double)trim(str_replace('$', '', $this->getPrice($item))),
                    'source' => 'cjcom',
                    'published_date' => $product_data->published_date,
                    'status' => $product_data->status,
                    'images' => []
                ];

                array_push($products, $product_details);
            }
        }

        return $products;
    }
    
    public function getImage($imageSets){
        if($imageSets[0]){
            $images = [];
            foreach ($imageSets as $imageSet) {
                if($imageSet['@attributes']['Category'] == 'primary'){
                    array_push($images, $imageSet);
                }
            }

            if(count($images) >  0)
                return $imageSets[0]['LargeImage']['URL'];
            else
                return $imageSets['LargeImage']['URL'];
        }else{
            return $imageSets['LargeImage']['URL'];
        }
    }

    public function getImages($imageSets){
        $images = [];

        if($imageSets[0]){
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
        if($product->{'sale-price'} && $product->{'sale-price'} > 0){
            return $product->{'sale-price'};
        }else if($product->price){
            return $product->price;
        }else{
            return '0.00';
        }
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
