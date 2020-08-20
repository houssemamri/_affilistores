<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\GlobalController;
use Auth;
use Input;
use Session;
use SimpleXMLElement;
use Route;
use Crypt;
use Purifier;
use Response;
use Alaouy\Youtube\Facades\Youtube;
use Thujohn\Twitter\Facades\Twitter;
use Carbon\Carbon;
use File;
use App\AffiliateSetting;
use App\Store;
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
use App\ProductReview;
use App\ProductBlog;
use App\Blog;
use App\SocialProof;
use App\AccessFeature;
use App\JvzooProduct;
use App\ClickBankProduct;
use App\WarriorPlusProduct;
use App\PayDotComProduct;
use App\ProductTrack;
use App\SocialCampaignLog;
use App\YoutubeKey;

class ProductController extends GlobalController
{
    private $store;

    public function __construct(){
        parent::__construct();
        
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();
    }

    public function index(){
        $products = Product::where('store_id', $this->store->id)->get();
        $affiliates = $this->getAffiliateStores();
        return view('products.index', compact('products', 'affiliates'));
    }

    public function getAffiliateStores(){
        $affiliates = [];
        $features = AccessFeature::where('membership_id', Auth::user()->memberDetail->membership->id)->get();

        foreach ($features as $feature) {
            if($feature->features->type == 'affiliate_store')
                array_push($affiliates, strtolower(str_replace('.', '', $feature->features->name)));
        }

        return $affiliates;
    }

    public function getFeatures(){
        $features = [];
        $accessFeatures = AccessFeature::where('membership_id', Auth::user()->memberDetail->membership->id)->get();
        foreach ($accessFeatures as $feature) {
            if($feature->features->type !== 'affiliate_store')
                array_push($features, strtolower(str_replace('.', '', $feature->features->name)));
        }

        return $features;
    }

    public function create(Request $request, $subdomain, $source){
        if(!$this->checkAffiliateStore($source)){
            Session::flash('error', 'Invalid access!');
            return redirect()->back();
        }
        $store = $this->store;
        $searchIndices = $this->getSearchIndices($source);
        $categories = Category::where('status', 1)->where('store_id', $this->store->id)->orderBy('name', 'ASC')->get();
        $tags = implode(',', Tag::where('store_id', $this->store->id)->pluck('name')->toArray());
        $products_reference = json_encode(Product::where('store_id', $this->store->id)->where('source', $source)->pluck('reference_id')->toArray());
        $tracks = $this->getTrackingProducts($store, $source);

        if($request->isMethod('POST')){
            if(!isset($request->category)){
                Session::flash('error', 'Please select atleast one category');
                return redirect()->back();
            }

            if(!isset($request->productsSelected)){
                Session::flash('error', 'Please select atleast one product');
                return redirect()->back();
            }

            $productTags = isset($request->tags) ? explode(',', $request->tags) : [];  
            $products = json_decode($request->productsSelected);

            //product tracking
            if(isset($request->productsTracking)){
                $trackings = json_decode($request->productsTracking);
                if(count($trackings) > 0){
                    foreach ($trackings as $tracking) {
                        $this->addProductTrack($source, $tracking->id, $tracking->status);
                    }
                }
            }

            foreach ($products as $product) {
                $productInfo = json_decode($product);
                $existing = Product::where('store_id', $this->store->id)->where('reference_id', $productInfo->product_id)->where('source', $source)->count();

                if($existing > 0) continue;
                
                $description = '';
                $images = [];

                if($source == 'amazon'){
                    $description = isset($productInfo->description) ? urldecode($productInfo->description) : '';
                    $images =  $productInfo->images;
                    $productInfo->details_link = $this->amazonAffiliateLink($productInfo->details_link);
                }elseif($source == 'ebay'){
                    $details = $this->ebayGetItemDetails($productInfo->product_id);
                    $description = isset($details['description']) ? $details['description'] : '';
                    $images = $details['images'];
                }elseif($source == 'aliexpress'){
                    $description = isset($productInfo->description) ? urldecode($productInfo->description) : '';
                    $images = $productInfo->images;
                }elseif($source == 'walmart'){
                    $description = isset($productInfo->description) ? urldecode($productInfo->description) : '';
                    $images =  $productInfo->images;
                }elseif($source == 'shopcom'){
                    $description = isset($productInfo->description) ? urldecode($productInfo->description) : '';
                    $images =  $productInfo->images;
                }elseif($source == 'jvzoo'){
                    $productInfo->details_link = 'https://jvz1.com/c/' . json_decode($this->store->affiliateSettings->where('name', 'jvzoo')->first()->settings)->affiliate_id . '/' . $productInfo->product_id ;
                    $thumbnailImg = $this->fetchImageThumbnailWS(urldecode($productInfo->sales_page));
                    $productInfo->image = isset($thumbnailImg) ? $thumbnailImg : $productInfo->image;
                    $description = isset($productInfo->description) ? urldecode($productInfo->description) : '';
                }elseif($source == 'clickbank'){
                    $productInfo->details_link = 'http://' . json_decode($this->store->affiliateSettings->where('name', 'clickbank')->first()->settings)->account_name . '.' . $productInfo->product_id .'.hop.clickbank.net';
                    $thumbnailImg = $this->fetchImageThumbnailWS($productInfo->details_link);
                    $productInfo->image = isset($thumbnailImg) ? $thumbnailImg : $productInfo->image;
                    $description = isset($productInfo->description) ? urldecode($productInfo->description) : '';
                }elseif($source == 'warriorplus'){
                    $thumbnailImg = $this->fetchImageThumbnailWS(urldecode($productInfo->offer_url));
                    $productInfo->image = isset($thumbnailImg) ? $thumbnailImg : $productInfo->image;
                    $description = isset($productInfo->description) ? urldecode($productInfo->description) : '';
                }elseif($source == 'paydotcom'){
                    $thumbnailImg = $this->fetchImageThumbnailWS(urldecode($productInfo->preview_url));
                    $productInfo->image = isset($thumbnailImg) ? $thumbnailImg : $productInfo->image;
                    $description = isset($productInfo->description) ? urldecode($productInfo->description) : '';
                }

                $product = Product::create([
                    'store_id' => $this->store->id,
                    'reference_id' => $productInfo->product_id,
                    'name' => strip_tags(Purifier::clean(urldecode($productInfo->title))),
                    'description' => Purifier::clean(htmlspecialchars($description)),
                    'permalink' => $productInfo->permalink,
                    'details_link' => urldecode($productInfo->details_link),
                    'image' => urldecode($productInfo->image),
                    // 'price' => (double)trim(str_replace('$', '', $productInfo->price)),
                    'price' => (double)trim(preg_replace('/[^0-9\.]/', '', $productInfo->price)),
                    'currency' => $productInfo->currency,
                    'source' => $source,
                    'published_date' => isset($request->publish) && $request->publish == 'now' ? Carbon::now() : $request->published_date,
                    'status' => isset($request->publish) && $request->publish == 'now' ? 1 : 0,
                    'auto_approve' => isset($request->auto_approve) ? 1 : 0,
                ]);
                
                $url = route('index.product.show', ['subdomain' => $this->store->subdomain, 'permalink' => $product->permalink]);
            
                $this->addProductCategory($request->category, $product->id);
                $this->addProductTag($productTags, $product->id);
                $this->addProductImage($images, $product->id);
                $this->addProductSeoSetting($product->id);
                $this->addProductTweets($product->id, $product->name);
                $this->addProductHits($product->id);
                $this->pingPingOMatic($product->name, $url);              
                $this->addProductVideo($product->id, $product->name);
            }

            Session::flash('success', 'Successfully added new products');
            return redirect()->back();
        }

        $blade = 'products.create.' . $source;

        return view($blade, compact('searchIndices', 'categories', 'tags', 'products_reference', 'source', 'store', 'tracks'));
    }

    public function amazonAffiliateLink($link){
        $settings = json_decode($this->store->affiliateSettings->where('name', 'amazon')->first()->settings);
        $affiliateLink = str_replace(env('AMAZON_ACCESS_KEY'), $settings->access_key_id, $link);
        $affiliateLink = str_replace(env('AMAZON_ASSOCIATE_TAG'), $settings->associate_tag, $affiliateLink);

        return $affiliateLink;
    }

    public function fetchImageThumbnailWS($url){
        $apiKey = env('THUMBNAIL_WS_API_KEY');
        $baseURL = "https://api.thumbnail.ws/api/" . $apiKey . "/thumbnail/get?";
        $thumbnailURL = $baseURL . "url=" .$url. "&width=480";
        $imageContents = $this->getContents($thumbnailURL);

        if(isset($imageContents)){
            $random =  str_shuffle($this->encode(rand(0, 100)));
            $filePath = 'img/uploads/'. $this->store->subdomain .'/products';
            $fileName = 'PRD_'. time() .'_'. $random . '.jpg';

            if(!File::exists($filePath))
                mkdir($filePath, 0777, true);

            file_put_contents($filePath.'/'.$fileName, $imageContents);

            return asset($filePath.'/'.$fileName);
        }
    }

    public function getContents($thumbnailURL){
        try {
            return (file_get_contents($thumbnailURL));
        } catch(\Exception $e){
            return null;
        }
    }

    public function checkAffiliateStore($source){
        $features = AccessFeature::where('membership_id', Auth::user()->memberDetail->membership->id)->get();

        foreach ($features as $feature) {
            if(strtolower(str_replace('.', '', $feature->features->name)) == strtolower($source)){
                return true;
            }
        }

        return false;
    }

    public function createAmazonManually(Request $request){
        $store = $this->store;
        $features = $this->getFeatures();
        $blogs = $this->store->blogs;
        $categories = Category::where('status', 1)->where('store_id', $this->store->id)->orderBy('name', 'ASC')->get();
        $tags = implode(',', Tag::where('store_id', $this->store->id)->pluck('name')->toArray());
        $currencies = [
            'AUD' => '$',
            'BRL' => 'R$',
            'CAD' => '$',
            'CNY' => '¥',
            'FRF' => 'F',
            'EUR' => '€',
            'INR' => '₹',
            'JPY' => '¥',
            'MXN' => '$',
            'TRY' => '₺',
            'GBP' => '£',
            'USD' => '$'
        ];

        if($request->isMethod('POST')){
            $this->validate($request, [
                'amazon_product_id' => 'required',
                'name' => 'required',
                'description' => 'required',
                'affiliate_link' => 'required',
                'currency' => 'required',
                'price' => 'required',
                'tags' => 'required',
            ]);

            if(!isset($request->tags)){
                Session::flash('error', 'Please add atleast one product tag');
                return redirect()->back()->withInput(Input::all());
            }

            if(!isset($request->category)){
                Session::flash('error', 'Please select atleast one category');
                return redirect()->back()->withInput(Input::all());
            }

            if(isset($request->blog_published) && $request->blog_type == 'manual_blog'){
                $this->validate($request, [
                    'blog_title' => 'required',
                    'blog_description' => 'required',
                ]);
            }

            $productTags = isset($request->tags) ? explode(',', $request->tags) : [];  

            $product = Product::create([
                'store_id' => $this->store->id,
                'reference_id' => $request->amazon_product_id,
                'name' => strip_tags(Purifier::clean(urldecode($request->name))),
                'description' => Purifier::clean(htmlspecialchars($request->description)),
                'permalink' => $this->clean($request->name),
                'details_link' => urldecode($request->affiliate_link),
                'image' => asset('img/uploads/category-default.jpg'),
                'price' => (double)trim(str_replace('$', '', $request->price)),
                'currency' => $request->currency,
                'source' => 'amazon',
                'published_date' => Carbon::now(),
                'status' => 1,
                'auto_approve' => isset($request->auto_approve) ? 1 : 0,
            ]);
            
            $url = route('index.product.show', ['subdomain' => $this->store->subdomain, 'permalink' => $product->permalink]);
        
            $this->addProductCategory($request->category, $product->id);
            $this->addProductTag($productTags, $product->id);
            $this->updateProductDefaultImage($request, $product->id);
            $this->updateProductImage($request->custom_image, $product->id);
            $this->addProductSeoSetting($product->id);
            $this->addProductTweets($product->id, $product->name);
            $this->addProductHits($product->id);
            $this->updateProductBlog($request, $product->id);
            $this->pingPingOMatic($product->name, $url);    
            $this->addProductVideo($product->id, $product->name);

            Session::flash('success', 'Amazon Product successfully added.');
            return redirect()->route('products.index', $store->subdomain);
        }

        return view('products.createamazonmanual', compact('store', 'features', 'tags', 'categories', 'blogs', 'currencies'));
    }

    public function edit(Request $request, $subdomain, $id){
        $decrypted = Crypt::decrypt($id);
        $product = Product::find($decrypted);

        if($this->checkProductDetails($product)){
            $product = Product::find($decrypted);
            $categories = Category::where('status', 1)->where('store_id', $this->store->id)->orderBy('name', 'ASC')->get();
            $features = $this->getFeatures();
            $tags = $this->arrayToString($product->tags);
            $blogs = $this->store->blogs;
            $empty = [];
            $currencies = [
                'AUD' => '$',
                'BRL' => 'R$',
                'CAD' => '$',
                'CNY' => '¥',
                'FRF' => 'F',
                'EUR' => '€',
                'INR' => '₹',
                'JPY' => '¥',
                'MXN' => '$',
                'TRY' => '₺',
                'GBP' => '£',
                'USD' => '$'
            ];

            if($request->isMethod('POST')){
                $this->validate($request, [
                    'name' => 'required',
                    'product_id' => 'required',
                    'price' => 'required',
                    'permalink' => 'required',
                    'affiliate_link' => 'required',
                ]);

                if(!isset($request->category)){
                    Session::flash('error', 'Please select atleast one category');
                    return redirect()->back();
                }
    
                if(!isset($request->tags)){
                    Session::flash('error', 'Please add atleast one product tag');
                    return redirect()->back();
                }
    
                if(isset($request->blog_published) && $request->blog_type == 'manual_blog'){
                    $this->validate($request, [
                        'blog_title' => 'required',
                        'blog_description' => 'required',
                    ]);
                }
    
                $productTags = isset($request->tags) ? explode(',', $request->tags) : '';            
    
                $product->update([
                    'name' => $request->name,
                    'reference_id' => $request->product_id,
                    'currency' => $request->currency,
                    'price' => $request->price,
                    'details_link' => $request->affiliate_link,
                    'description' => Purifier::clean(($request->description)),
                    'permalink' => $request->permalink,
                    'auto_approve' => isset($request->auto_approve) ? 1 : 0,
                    'show_tweets' => isset($request->show_related_tweets) ? 1 : 0,
                ]);
                
                $url = route('index.product.show', ['subdomain' => $this->store->subdomain, 'permalink' => $product->permalink]);
    
                $this->updateProductDefaultImage($request, $product->id);
                $this->updateProductCategory($request->category, $product->id);
                $this->updateProductTag($productTags, $product->id);
                $this->updateProductImage($request->custom_image, $product->id);
                $this->updateProductVideo($request, $product->id);
                $this->updateProductSeoSetting($request, $product->id);
                $this->updateProductBlog($request, $product->id);
                $this->pingPingOMatic($product->name, $url);
    
                Session::flash('success', 'Product successfully updated');
                return redirect()->back();
                // return redirect()->route('products.index', $this->store->subdomain);
            }  

            return view('products.edit', compact('product', 'id', 'categories', 'tags', 'blogs', 'features', 'currencies'));
        }else{
            Session::flash('success', 'Oops something went wrong!');
            return redirect()->back();
        }
    }

    public function checkProductDetails($product){
        if(!isset($product->seoSettings)){
            $this->addProductSeoSetting($product->id);
        }

        if(!isset($product->hits)){
            $this->addProductHits($product->id);
        }
        if(count($product->videos) == 0){
            $this->addProductVideo($product->id, $product->name);
        }

        return true;
    }

    public function delete(Request $request){
        $decrypted = Crypt::decrypt($request->product_id);
        $product = Product::find($decrypted);

        //delete product categories
        $productCategories = ProductCategory::where('product_id', $product->id)->delete();
        //delete product tags
        $productTags = ProductTag::where('product_id', $product->id)->delete();
        //delete product images
        $productImages = ProductImage::where('product_id', $product->id)->get();

        foreach ($productImages as $image) {
            if($image->type == 'custom')
                $this->removeFromDirectory($image->image);

            $image->delete();
        }
        
        //delete product video
        $productVideo = ProductVideo::where('product_id', $product->id)->delete();
        //delete product seo settings
        $productSeoSetting = ProductSeoSetting::where('product_id', $product->id)->delete();
        //delete product tweets
        $productTweets = ProductTweet::where('product_id', $product->id)->delete();
        //delete product hits
        $productHits = ProductHit::where('product_id', $product->id)->delete();
        //delete product reviews
        $productReviews = ProductReview::where('product_id')->delete();
        //delete product reviews
        $productBlogs = ProductBlog::where('product_id')->delete();
        //delete social campaign
        $socialCampaignLog = SocialCampaignLog::where('product_id', $product->id)->delete();

        if($product->delete())
            Session::flash('success', 'Product successfully deleted');
        else
            Session::flash('error', 'Oops! Something went wrong, Please try again');

        return redirect()->back();
    }

    public function deleteMultiple(Request $request){
        $productIds = explode(',', $request->deleteProducts);

        foreach ($productIds as $productId) {
            if($productId !== ''){
                $product = Product::where('store_id', $this->store->id)->where('reference_id', $productId)->first();

                if(isset($product)){
                    //delete product categories
                    $productCategories = ProductCategory::where('product_id', $product->id)->delete();
                    //delete product tags
                    $productTags = ProductTag::where('product_id', $product->id)->delete();
                    //delete product images
                    $productImages = ProductImage::where('product_id', $product->id)->get();
            
                    foreach ($productImages as $image) {
                        if($image->type == 'custom')
                            $this->removeFromDirectory($image->image);
            
                        $image->delete();
                    }
                    
                    //delete product video
                    $productVideo = ProductVideo::where('product_id', $product->id)->delete();
                    //delete product seo settings
                    $productSeoSetting = ProductSeoSetting::where('product_id', $product->id)->delete();
                    //delete product tweets
                    $productTweets = ProductTweet::where('product_id', $product->id)->delete();
                    //delete product hits
                    $productHits = ProductHit::where('product_id', $product->id)->delete();

                    $product->delete();
                }
            }
        }

        Session::flash('success', 'Products successfully deleted');
        return redirect()->back();
    }
    
    public function reviews(Request $request, $subdomain, $id){
        $decrypted = Crypt::decrypt($id);
        $product = Product::find($decrypted);

        return view('products.reviews', compact('product'));
    }

    public function apporoveDisapprove(Request $request, $subdomain, $id, $status){
        $decrypted = Crypt::decrypt($id);
        $review = ProductReview::find($decrypted);

        $review->update([
            'approved' => $status
        ]);

        if($status == 1){
            $data = [
                'image' => ($review->product->image),
                'url' => (route('index.product.show', ['subdomain' => $this->store->subdomain, 'permalink' => $review->product->permalink])),
                'ratings' => $review->ratings,
                // 'customer' => addslashes($review->name),
                'title' => 'New Customer Review',
                'content' => ($review->review),
                'settings' => [
                    'display_time' => '10',
                    'time_difference' => '10',
                ],
            ];

            SocialProof::create([
                'store_id' => $this->store->id,
                'content' => json_encode($data),
                'type' => 'review',
                'active' => 0,
            ]);
        }

        Session::flash('success', $status == 1 ? 'Successfully approved product review' : 'Successfully disapproved product review');
        return redirect()->back();
    }

    public function checkFeatures($name){
        $features = AccessFeature::where('membership_id', Auth::user()->memberDetail->membership->id)->get();

        foreach ($features as $feature) {
            if(strtolower($feature->features->name) == strtolower($name)){
                return true;
            }
        }

        return false;
    }

    public function getTrackingProducts($store, $source){
        $tracks = [];
        $products = ProductTrack::where('store_id', $store->id)->where('source', $source)->get();

        foreach ($products as $product) {
            $tracks[$product->reference_id] = $product->status;
        }

        return json_encode($tracks);
    }

    public function addProductTrack($source, $reference_id, $status){
        $track = ProductTrack::where('store_id', $this->store->id)->where('reference_id', $reference_id)->where('source', $source)->first();

        if(isset($track)){
            $track->update([
                'status' => $status
            ]);
        }else{
            ProductTrack::create([
                'store_id' => $this->store->id,
                'reference_id' => $reference_id,
                'source' => $source,
                'status' => $status
            ]);
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

    public function addProductTag($tags, $product_id){
        $productTags = ProductTag::where('product_id', $product_id)->delete();

        foreach ($tags as $tag) {
            //create tag
            $tagExist = Tag::where('name', 'like', $tag)->where('store_id', $this->store->id)->first();

            if(!isset($tagExist)){
                $tag = Tag::create([
                    'store_id' => $this->store->id,
                    'name' => strtolower($tag),
                    'status' => 1
                ]);
                $tagId = $tag->id;
            }else{
                $tagId = $tagExist->id;
            }
           
            //create product tag
            ProductTag::create([
                'tag_id' => $tagId,
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

    public function addProductVideo($product_id, $keyword){
        if($this->checkFeatures('YouTube Videos')){
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

    public function addProductTweets($product_id, $keyword){
        if($this->checkFeatures('Related Tweets')){
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

    public function addProductHits($product_id){
        ProductHit::create([
            'store_id' => $this->store->id,
            'product_id' => $product_id,
        ]);
    }

    public function updateProductCategory($categories, $product_id){
        $category = ProductCategory::where('product_id', $product_id)->delete();
        
        foreach ($categories as $category) {
            ProductCategory::create([
                'category_id' => $category,
                'product_id' => $product_id,
            ]);
        }
    }

    public function updateProductTag($tags, $product_id){
        $productTags = ProductTag::where('product_id', $product_id)->delete();

        foreach ($tags as $tag) {
            //create tag
            $tagExist = Tag::where('name', 'like', $tag)->first();

            if(!isset($tagExist)){
                $tag = Tag::create([
                    'store_id' => $this->store->id,
                    'name' => strtolower($tag),
                    'status' => 1
                ]);
                $tagId = $tag->id;
            }else{
                $tagId = $tagExist->id;
            }
           
            //create product tag
            ProductTag::create([
                'tag_id' => $tagId,
                'product_id' => $product_id,
            ]);
        }
    }

    public function updateProductDefaultImage($request, $product_id){
        if(isset($request->default_image)){
            $image = $request->default_image;
            $random =  str_shuffle($this->encode(rand(0, 100)));
            $fileExtension = $image->getClientOriginalExtension();
            $fileName = 'PRD_'.$product_id.'_' . time() .'_'. $random . '.' . $fileExtension;
            $filePath = 'img/uploads/'.$this->store->subdomain.'/products';
            
            $image->move($filePath, $fileName);

            $product = Product::where('id', $product_id)->first();
            $product->update([
                'image' => asset($filePath.'/'.$fileName)
            ]);
        }elseif (isset($request->default_image_link)) {
            $product = Product::where('id', $product_id)->first();
            $product->update([
                'image' => $request->default_image_link
            ]);
        }
    }

    public function updateProductImage($images, $product_id){
        if(isset($images)){
            foreach ($images as $key => $image) {
                $random =  str_shuffle($this->encode($key));
                $fileExtension = $image->getClientOriginalExtension();
                $fileName = 'PRD_'.$product_id.'_' . time() .'_'. $random . '.' . $fileExtension;
                $filePath = 'img/uploads/'.$this->store->subdomain.'/products';
                
                $image->move($filePath, $fileName);

                ProductImage::create([
                    'product_id' => $product_id,
                    'image' => asset($filePath.'/'.$fileName),
                    'type' => 'custom'
                ]);
            }
        }
    }

    public function updateProductVideo($request, $product_id){
        $ctr = 0;

        if(isset($request->video_id)){
            foreach ($request->video_id as $video) {
                $productVideo = ProductVideo::find($video);
    
                $productVideo->update([
                    'product_id' => $product_id,
                    'video' => $request->video_link[$ctr],
                    'status' => isset($request->video_status[$ctr]) ? 1 : 0,
                ]);
    
                $ctr++;
            }
        }
    }

    public function updateProductSeoSetting($request, $product_id){
        $productSeoSetting = ProductSeoSetting::where('product_id', $product_id)->first();
        
        $productSeoSetting->update([
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'robots_meta_no_index' => isset($request->robots_meta_no_index) ? 1 : 0,
            'robots_meta_no_follow' => isset($request->robots_meta_no_follow) ? 1 : 0,
        ]);
    }

    public function updateProductBlog($request, $product_id){
        if(isset($request->blog_title) && isset($request->blog_description)){
            $productBlog = ProductBlog::where('product_id', $product_id)->first();

            if($request->blog_type == 'get_blog'){
                $blog = Blog::find($request->blog_id);
                $title = $blog->title;
                $description = $blog->post;
            }else{
                $title = $request->blog_title;
                $description = Purifier::clean(htmlspecialchars($request->blog_description));
            }

            if(!isset($productBlog)){
                ProductBlog::create([
                    'store_id' => $this->store->id,
                    'product_id' => $product_id,
                    'title' => $title,
                    'description' => $description,
                    'published' => isset($request->blog_published) ? 1 : 0
                ]);
            }else{
                $productBlog->update([
                    'title' => $title,
                    'description' => $description,
                    'published' => isset($request->blog_published) ? 1 : 0
                ]);
            }
        }
    }

    public function ebayGetItemDetails($itemId){
        $ebay = AffiliateSetting::where('store_id', $this->store->id)->where('name', 'ebay')->first();
        $settings = json_decode($ebay->settings);
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
            $details['description'] = Purifier::clean(htmlspecialchars($content->Item->Description));
            $details['images'] = $content->Item->PictureURL;
            
            return $details;
        } catch (Exception $e) {
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    public function getSearchIndices($source){
        $amazon = [
            'All',
            'Apparel',
            'Automotive',
            'Baby',
            'Beauty',
            'Books',
            'DVD',
            'Electronics',
            'GiftCards',
            'Grocery',
            'HealthPersonalCare',
            'HomeGarden',
            'Jewelry',
            'KindleStore',
            'Luggage',
            'Music',
            'MusicalInstruments',
            'OfficeProducts',
            'PCHardware',
            'PetSupplies',
            'Shoes',
            'Software',
            'SportingGoods',
            'Toys',
            'VideoGames',
            'Watches',
            'Wireless'
        ];

        $ebay = [
            'All' => '1',
            'Art' => '550',
            'Baby' => '2984',
            'Books, Comics &amp; Magazines' => '267',
            'Business, Office &amp; Industrial' => '12576',
            'Cameras & Photography' => '625',
            'Cars, Motorcycles &amp; Vehicles' => '6001',
            'Clothes, Shoes &amp; Accessories' => '11450',
            'Coins' => '11116',
            'Computers/Tablets &amp; Networking' => '58058',
            'Consumer Electronics' => '293',
            'Crafts' => '14339',
            'Dolls &amp; Bears' => '237',
            'DVDs, Films &amp; TV' => '11232',
            'Events Tickets' => '1305',
            'Mobile Phones &amp; Communication' => '15032',
            'Health &amp; Beauty' => '26395',
            'Garden &amp; Patio' => '159912',
            'Jewelry &amp; Watches' => '281',
            'Music' => '11233',
            'Musical Instruments' => '619',
            'Pet Supplies' => '1281',
            'Pottery, Porcelain &amp; Glass' => '870',
            'Property' => '10542',
            'Sporting Goods' => '888',
            'Sound &amp; Vision' => '293',
            'Stamps' => '260',
            'Sports Memorabilia' => '64482',
            'Toys &amp; Hobbies' => '220',
            'Video Games &amp; Consoles' => '1249',
            'Decals, Stickers &amp; Vinyl Art' => '159889',
        ];

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

        $cjcom = [];
        $jvzoo = [];
        $clickbank = [];
        $warriorplus = [];
        $paydotcom = [];

        return ${$source};
    }

    public function arrayToString($productTags){
        $tags = [];
        foreach ($productTags as $tag)
            array_push($tags, $tag->tag->name);
        
        $stringTags = implode (", ", $tags);

        return $stringTags;
    }

    public function encode($num) {
        $scrambled = (240049382*$num + 37043083) % 308915753;
        return base_convert($scrambled, 10, 6);
    }

    public function deleteImage(Request $request){
        $decrypted = Crypt::decrypt($request->image_id);
        $productImage = ProductImage::find($decrypted);
        $imgLink = explode("/", $productImage->image);
        $image = array_pop($imgLink);


        if($productImage->delete()){
            $this->removeFromDirectory($image);

            return Response::json(['msg' => 'success']);
        }else
            return Response::json(['msg' => 'error']);
    }

    public function removeFromDirectory($image){
        $filePath = 'img/uploads/'.$this->store->subdomain.'/products'.'/'. $image;
        
        if(file_exists($filePath)) 
            unlink($filePath); 

        return true;
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

    public function pingPingOMatic($title, $url)
    {
        $entity = [
            'chk_weblogscom'    => 'on',
            'chk_blogs'         => 'on',
            'chk_feedburner'    => 'on',
            'chk_newsgator'     => 'on',
            'chk_myyahoo'       => 'on',
            'chk_pubsubcom'     => 'on',
            'chk_blogdigger'    => 'on',
            'chk_weblogalot'    => 'on',
            'chk_newsisfree'    => 'on',
            'chk_topicexchange' => 'on',
            'chk_google'        => 'on',
            'chk_tailrank'      => 'on',
            'chk_skygrid'       => 'on',
            'chk_collecta'      => 'on',
            'chk_superfeedr'    => 'on',
        ];

        $entity['title'] = urlencode($title);
        $entity['blogurl'] = urlencode($url);

        $query_string = http_build_query($entity);
        $service_url = 'http://pingomatic.com/ping/?'.$query_string;

        return $this->sendPing($service_url, $query_string);
    }

    private function sendPing($url, $post)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    public function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = trim(preg_replace('/-+/', '-', $string), '-');
		$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
		$string = strtolower($string); // Convert to lowercase
 
		return $string;
    }
}
