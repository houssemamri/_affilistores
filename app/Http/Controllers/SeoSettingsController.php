<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use Route;
use Purifier;
use Session;
use Crypt;
use Storage;
use Carbon\Carbon;
use App\Store;
use App\SeoSettingsHomePage;
use App\SeoSettingsArchivePage;
use App\SeoSettingsProductPage;
use App\SeoSettingsTitleSetting;
use App\SeoSettingsWebmastersSetting;
use App\SeoSettingsAnalytic;
use App\Product;
use App\Category;
use App\ProductCategory;
use App\AccessFeature;

class SeoSettingsController extends GlobalController
{
    private $store;
    private $features;

    public function __construct(){
        parent::__construct();
        
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();
        $this->features = $this->getFeatures();
    }

    public function index(Request $request){
        $store = $this->store;
        $sitemap = $this->xmlSiteMap();
        $categories = Category::where('store_id', $this->store->id)->get();
        $features = $this->getFeatures();

        if($request->isMethod('POST')){
            if(isset($_POST['btn_homepage'])){
                $this->validate($request, [
                    'website_name' => 'required',
                ]);
                
                $store->homePage->update([
                    'website_name' => $request->website_name, 
                    'meta_title' => $request->meta_title, 
                    'meta_description' => $request->meta_description, 
                    'meta_keywords' => $request->meta_keywords, 
                    'robots_meta_no_index' => isset($request->robots_meta_no_index) ? 1 : 0, 
                    'robots_meta_no_follow' => isset($request->robots_meta_no_follow) ? 1 : 0
                ]);

                Session::flash('success', 'Homepage settings successfully saved');
            }elseif(isset($_POST['btn_productpage'])){
                Session::flash('seo_selected', 'productpage');
                
                $this->validate($request, [
                    'meta_title' => 'required',
                ]);
                
                $store->productPage->update([
                    'meta_title' => $request->meta_title, 
                    'robots_meta_no_index' => isset($request->robots_meta_no_index) ? 1 : 0, 
                    'robots_meta_no_follow' => isset($request->robots_meta_no_follow) ? 1 : 0
                ]);

                Session::flash('success', 'Product page settings successfully saved');
            }elseif(isset($_POST['btn_archivepage'])){
                Session::flash('seo_selected', 'archivepage');
                
                $this->validate($request, [
                    'archive_meta_title' => 'required',
                ],
                [
                    'archive_meta_title.required' => "The meta title field is required.",
                ]);
                
                $store->archivePage->update([
                    'meta_title' => $request->archive_meta_title, 
                    'robots_meta_no_index' => isset($request->robots_meta_no_index) ? 1 : 0, 
                    'robots_meta_no_follow' => isset($request->robots_meta_no_follow) ? 1 : 0
                ]);

                Session::flash('success', 'Archive page settings successfully saved');
            }elseif(isset($_POST['btn_titlesettings'])){
                Session::flash('seo_selected', 'titlesettings');

                $this->validate($request, [
                    'search_page_title' => 'required',
                    'error_title_format' => 'required',
                ],[
                    'error_title_format.required' => '404 page title format	field is required'
                ]);
                
                $store->titleSettings->update([
                    'search_page_title' => $request->search_page_title, 
                    'error_page_title' => $request->error_title_format, 
                ]);

                Session::flash('success', 'Title settings successfully saved');
            }elseif(isset($_POST['btn_webmasterssettings'])){
                Session::flash('seo_selected', 'webmasterssettings');
                
                $store->webmasterSettings->update([
                    'google_verification_code' => $request->google_verification_code, 
                    'bing_verification_code' => $request->bing_verification_code, 
                    'pinterest_verification_code' => $request->pinterest_verification_code, 
                ]);

                Session::flash('success', 'Webmasters settings successfully saved');
            }elseif(isset($_POST['btn_analytics'])){
                Session::flash('seo_selected', 'analytics');
                
                $store->analytics->update([
                    'google_analytics_tracking_code' => $request->google_analytics_tracking_code, 
                    'third_party_analytics_tracking_code' => $request->third_party_analytics_tracking_code, 
                    'facebook_remarketing_pixel_script' => $request->facebook_remarketing_pixel_script, 
                    'webengage_tracking_id' => $request->webengage_tracking_id, 
                ]);

                Session::flash('success', 'Analytics settings successfully saved');
            }

            return redirect()->back();
        }

        return view('seo-settings.index', compact('store', 'sitemap', 'categories', 'features'));
    }

    public function xmlSiteMap(){
        // create new sitemap object
        $sitemap = \App::make('sitemap');
        $dt = Carbon::now();
               
        $home = route('redirectDashboard', ['subdomain' => $this->store->subdomain]);
        $sitemap->add($home, $dt->toDateTimeString(), '1.0', 'monthly');

        $categories = Category::where('store_id', $this->store->id)->get();
        foreach ($categories as $category) {
            $url = route('index.category', ['subdomain' => $this->store->subdomain, 'category' => $category->name]);
            $sitemap->add($url, $category->created_at, '0.5', 'monthly');

        }

        foreach ($categories as $category) {
            $products = ProductCategory::where('category_id', $category->id)->get();
            
            foreach ($products as $product) {
                $url = route('index.product.show', ['subdomain' => $this->store->subdomain, 'permalink' => $product->product->permalink]);
                $sitemap->add($url, $category->created_at, '0.5', 'monthly');
            }                
        }

        // show your sitemap (options: 'xml' (default), 'html', 'txt', 'ror-rss', 'ror-rdf')
        return $sitemap->render('xml')->withHeaders([
            'Content-Type' => 'text/xml'
        ]);
    }

    public function rssfeed($subdomain, $categoryId = null){
        $productFeeds = \App::make("feed");
        
        // creating rss feed with our most recent 20 records in news table
        $products = !isset($categoryId) 
                        ? Product::where('store_id', $this->store->id)->orderBy('id', 'DESC')->get() 
                        : ProductCategory::where('category_id', $categoryId)->orderBy('id', 'DESC')->get();
                        
        // set your feed's title, description, link, pubdate and language
        $productFeeds->title = $this->store->name;
        $productFeeds->link = route('index', $this->store->subdomain);
        $productFeeds->setDateFormat('datetime'); // 'datetime', 'timestamp' or 'carbon'
        $productFeeds->pubdate = $this->store->created_at; // date of latest news
        $productFeeds->lang = 'en';
        $productFeeds->setShortening(true); // true or false
        $productFeeds->setTextLimit(500); // maximum length of description text

        foreach ($products as $product)
        {
            $product = !isset($categoryId) ? $product : $product->product;
            // set item's title, url, description, price, 'image', pubdate
            $productFeeds ->add(
                $product->name,
                route('index.product.show', ['subdomain' => $this->store->subdomain, 'permalink' => $product->permalink]),
                $product->description,
                $product->price,
                $product->image,
                $product->created_at
            );
        }

        // return your feed ('atom' or 'rss' format)
        return $productFeeds->render('atom')->withHeaders([
            'Content-Type' => 'text/xml'
        ]);
    }

    public function getFeatures(){
        $features = [];
        $accessFeatures = AccessFeature::where('membership_id', $this->store->user->memberDetail->membership->id)->get();
        
        foreach ($accessFeatures as $feature) {
            if($feature->features->type !== 'affiliate_store')
                array_push($features, strtolower(str_replace('.', '', $feature->features->name)));
        }

        return $features;
    }
}
