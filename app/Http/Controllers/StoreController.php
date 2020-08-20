<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use Session;
use DateTime;
use Crypt;
use Auth;
use File;
use App\Store;
use App\AffiliateSetting;
use App\SocialSetting;
use App\SmoSetting;
use App\StoreTheme;
use App\StoreSlider;
use App\StoreCategoryMenu;
use App\StoreLegalPage;
use App\StoreBannerAd;
use App\Theme;
use App\ColorScheme;
use App\Slider;
use App\SeoSettingsHomePage;
use App\SeoSettingsArchivePage;
use App\SeoSettingsProductPage;
use App\SeoSettingsTitleSetting;
use App\SeoSettingsWebmastersSetting;
use App\SeoSettingsAnalytic;
use App\StoreSocialProofSetting;
use App\ContactUsProfile;
use App\ExitPop;
use App\FacebookCustomerChat;
use App\Product;
use App\ProductCategory;
use App\ProductTag;
use App\ProductImage;
use App\ProductVideo;
use App\ProductSeoSetting;
use App\ProductTweet;
use App\ProductHit;
use App\Category;
use App\Tag;
use App\FacebookCommentPlugin;
use App\GetResponseSetting;
use App\Autoresponder;

class StoreController extends GlobalController
{
    public function index(){
        $stores = Store::all();

        return view('store.index', compact('stores'));
    }

    public function listStore(){
        $stores = Store::where('user_id', Auth::user()->id)->get();
        $countStoreThisMonth = Store::where('user_id', Auth::user()->id)->whereMonth('created_at', date('m'))->count();
        $limit = Auth::user()->memberDetail->membership->stores_per_month;
        $domain = $this->getDomain();

        return view('store', compact('stores', 'domain', 'countStoreThisMonth', 'limit'));
    }

    public function createStore(Request $request){
        $domain = $this->getDomain();

        if($request->isMethod('POST')){
            $this->validate($request, [
                'subdomain' => 'required|unique:stores,subdomain',
                'name' => 'required',
            ],
            [
                'subdomain.required' => "The subdomain field is required.",
                'name.required' => "The store name field is required.",
                'subdomain.unique' => "The subdomain is already used.",
            ]);

            $logo = ($request->file('logo')) ? $this->uploadLogo($request, strtolower($request->subdomain)): '';
            
            $store = Store::create([
                'user_id' => Auth::user()->id,
                'name' => $request->name,
                'subdomain' => strtolower($request->subdomain),
                'logo' => $logo
            ]);

            //setup settings in affliates, social, smo
            $this->setupSettings($store->id);
            //setup emtyp business profile
            $this->setupBusinessProfile($store->id);
            //setup theme
            $this->setupTheme($store->id);
            //setup slider
            $this->setupSlider($store->id);
            //setup banner ad
            $this->setupBannerAd($store->id);
            //setup legal pages
            $this->setupLegalPages($store->id);
            
            // setup SEO settings //
            //setup home page
            $this->setupHomePage($store->id);            
            //setup product page
            $this->setupProductPage($store->id);
            //setup archive page
            $this->setupArchivePage($store->id);
            //setup title settings
            $this->setupTitleSettings($store->id);
            //setup xml sitemaps
            $this->setupXmlSitemap($store->id);
            //setup rss feed
            $this->setupRssFeed($store->id);
            //setup webmaster settings
            $this->setupWebmasterSettings($store->id);
            //setup analytics
            $this->setupAnalytics($store->id);
            //setup analytics
            $this->socialProofSettings($store->id);

            // increase conversion

            //setup facebook customer chat
            $this->setupFacebookCustomerChat($store->id);
            //setup facebook comment plugin
            $this->setupFacebookCommentPlugin($store->id);

            //setup directory 
            if(!File::exists('img/uploads/'.$store->subdomain)) 
                mkdir('img/uploads/'.$store->subdomain, 0777, true);
            
            Session::flash("success", "Affiliate store successfully saved");

            return redirect()->route('listStore');
        }

        return view('store.create', compact('domain'));
    }

    public function createFirstStore(){
        if(!Session::has('first_store'))
            return redirect()->route('listStore');

        $data = Session::get('first_store');

        $store = Store::create([
            'user_id' => $data->user_id,
            'name' => $data->store_name,
            'subdomain' => strtolower($data->store_name),
            'logo' => ''
        ]);

        //setup settings in affliates, social, smo
        $this->setupSettings($store->id);
        //setup emtyp business profile
        $this->setupBusinessProfile($store->id);
        //setup theme
        $this->setupTheme($store->id);
        //setup slider
        $this->setupSlider($store->id);
        //setup banner ad
        $this->setupBannerAd($store->id);
        //setup legal pages
        $this->setupLegalPages($store->id);
        
        // setup SEO settings //
        //setup home page
        $this->setupHomePage($store->id);            
        //setup product page
        $this->setupProductPage($store->id);
        //setup archive page
        $this->setupArchivePage($store->id);
        //setup title settings
        $this->setupTitleSettings($store->id);
        //setup xml sitemaps
        $this->setupXmlSitemap($store->id);
        //setup rss feed
        $this->setupRssFeed($store->id);
        //setup webmaster settings
        $this->setupWebmasterSettings($store->id);
        //setup analytics
        $this->setupAnalytics($store->id);
        //setup analytics
        $this->socialProofSettings($store->id);

        // increase conversion

        //setup facebook customer chat
        $this->setupFacebookCustomerChat($store->id);
        //setup facebook comment plugin
        $this->setupFacebookCommentPlugin($store->id);

        //setup directory 
        if(!File::exists('img/uploads/'.$store->subdomain)) 
            mkdir('img/uploads/'.$store->subdomain);
            
        return redirect()->route('dashboard', ['subdomain' => $store->subdomain]);
    }

    public function setupTheme($storeId){
        $theme = Theme::get()->first();
        $colorScheme = ColorScheme::get()->first();
        $footer_settings = [
            'about' => '',
            'newsletter' => [
                'heading' => '',
                'text' => ''
            ]
        ];
        
        $storeTheme = StoreTheme::create([
            'theme_id' => $theme->id,
            'color_scheme_id' => $colorScheme->id,
            'store_id' => $storeId,
            'footer_settings' => json_encode($footer_settings),
        ]);
    }

    public function setupSlider($storeId){
        for ($i=1; $i <= 5 ; $i++) { 
            $slider = Slider::create([
                'main_tagline' => '', 
                'main_tagline_font_size' => '', 
                'sub_tagline' => '', 
                'sub_tagline_font_size' => '', 
                'cta_button_one_text' => '', 
                'cta_button_one_link' => '', 
                'cta_button_two_text' => '', 
                'cta_button_two_link' => '', 
                'image' => '', 
            ]);

            $storeSlider = StoreSlider::create([
                'slider_id' => $slider->id,
                'store_id' => $storeId,
                'slider_number' => $i
            ]);
        }
    }

    public function setupBannerAd($storeId){
        $settings = [
            'ImageUpload' => [
                'banner_image' => '',
                'banner_link' => ''
            ],  
            'GoogleAdSense' => [
                'code' => ''
            ], 
            'MenuBanner' => [
                'image' => '',
                'link' => ''
            ],
            'MenuBannerAdSense' => [
                'code' => '',
            ]
            // 'FacebookPixel' => [
            //     'code' => ''
            // ]
        ];

        foreach ($settings as $key => $value) {
            StoreBannerAd::create([
                'store_id' => $storeId,
                'type' =>  $key,
                'content' => json_encode($value)
            ]);
        }
    }
    
    public function setupLegalPages($storeId){
        try{
            $privacy = File::get(storage_path() . '/app/template/privacy-policy.html');
            $termsCondition = File::get(storage_path() . '/app/template/terms-condition.html');
            $gdprCompliance = File::get(storage_path() . '/app/template/gdpr-compliance.html');
            $affiliateDisclosure = File::get(storage_path() . '/app/template/affiliate-disclosure.html');
            $cookiePolicy = File::get(storage_path() . '/app/template/cookie-policy.html');
        }catch(\Exception $e){
            $privacy = '';
            $termsCondition = '';
            $gdprCompliance = '';
            $affiliateDisclosure = '';
            $cookiePolicy = '';
        }

        StoreLegalPage::create([
            'store_id' => $storeId,
            'terms_conditions' => $privacy,
            'privacy_policy' => $termsCondition,
            'contact_us' => '',
            'gdpr_compliance' => $gdprCompliance,
            'affiliate_disclosure' => $affiliateDisclosure,
            'cookie_policy' => $cookiePolicy
        ]);
    }

    public function setupHomePage($storeId){
        SeoSettingsHomePage::create([
            'store_id' => $storeId
        ]);
    }

    public function setupProductPage($storeId){
        SeoSettingsProductPage::create([
            'store_id' => $storeId
        ]);
    }

    public function setupArchivePage($storeId){
        SeoSettingsArchivePage::create([
            'store_id' => $storeId
        ]);
    }
    
    public function setupTitleSettings($storeId){
        SeoSettingsTitleSetting::create([
            'store_id' => $storeId
        ]);
    }

    public function setupXmlSitemap($storeId){
        
    }

    public function setupRssFeed($storeId){
        
    }

    public function setupWebmasterSettings($storeId){
        SeoSettingsWebmastersSetting::create([
            'store_id' => $storeId
        ]);
    }

    public function setupAnalytics($storeId){
        SeoSettingsAnalytic::create([
            'store_id' => $storeId
        ]);
    }

    public function socialProofSettings($storeId){
        StoreSocialProofSetting::create([
            'store_id' => $storeId,
            'settings' => json_encode(['display_order' => 'random'])
        ]);
    }

    public function editStore(Request $request){
        $store = Store::find($request->store_id);
        $domain = $this->getDomain();
        
        $this->validate($request, [
            'subdomain' => 'required|unique:stores,subdomain,' . $store->id,
            'name' => 'required',
        ],
        [
            'subdomain.required' => "The subdomain field is required.",
            'name.required' => "The store name field is required.",
            'subdomain.unique' => "The subdomain is already used.",
        ]);

        $logo = ($request->file('logo')) ? $this->uploadLogo($request, $store->subdomain): $store->logo;
        
        $store->update([
            'name' => $request->name,
            'subdomain' => strtolower($request->subdomain),
            'logo' => $logo
        ]);

        Session::flash("success", "Affiliate " .$store->name. " store successfully updated");
        return redirect()->route('listStore');
    }

    public function changeStatus($id, $status){
        $store = Store::where('id', Crypt::decrypt($id))->first();

        if(isset($store)){
            $store->update([
                'status' => $status == 'offline' ? 0 : 1
            ]);
            
            Session::flash('success', 'Store '. $store->name .' is now ' . $status);
        }else{
            Session::flash('error', 'Oops something went wrong. Invalid store id.');
        }

        return redirect()->back();
    }

    public function deleteStore(Request $request){
        if($request->isMethod('POST')){
            $store = Store::find($request->store_id);
            if(!isset($store)){
                Session::flash("error", "Cannot find store to delete. Please try again.");
                return redirect()->route('listStore');
            }
    
            $directory = base_path('img/uploads/' . $store->subdomain . '/');
            
            $alliates_settings = AffiliateSetting::where('store_id', $store->id)->delete();
            $social_settings = SocialSetting::where('store_id', $store->id)->delete();
            $smo_settings = SmoSetting::where('store_id', $store->id)->delete();
    
            //delete store busines profile 
            $businessProfile = ContactUsProfile::where('store_id', $store->id)->delete();
            // store settings
            $store_theme = StoreTheme::where('store_id', $store->id)->delete();
            //slider settings
            $sliderIds = StoreSlider::where('store_id', $store->id)->pluck('slider_id')->toArray();
            foreach ($sliderIds as $key => $value) 
                $sliders = Slider::where('id', $value)->delete();
           
            $storeSlider = StoreSlider::where('store_id', $store->id)->delete();
            //delete banner ads
            $storeBannerAds = StoreBannerAd::where('store_id', $store->id)->delete();
            //delete legal pages
            $storeLegalPage = StoreLegalPage::where('store_id', $store->id)->delete();
            //delete store nav menu
            $storeCategoryMenus = StoreCategoryMenu::where('store_id', $store->id)->delete();
            //seo settings
            $seoHomePage = SeoSettingsHomePage::where('store_id', $store->id)->delete();
            //setup product page
            $seoProductPage = SeoSettingsProductPage::where('store_id', $store->id)->delete();
            //setup archive page
            $seoArchivePAge = SeoSettingsArchivePage::where('store_id', $store->id)->delete();
            //setup title settings
            $seoTitleSettings = SeoSettingsTitleSetting::where('store_id', $store->id)->delete();
            //setup webmaster settings
            $seoWebmastersSettings = SeoSettingsWebmastersSetting::where('store_id', $store->id)->delete();
            //setup analytics
            $seoAnalytics = SeoSettingsAnalytic::where('store_id', $store->id)->delete();
            //delete exit pops
            $exitPops = ExitPop::where('store_id', $store->id)->delete();
            //delete facebook customer chat plugin
            $facebookCustomerChat = FacebookCustomerChat::where('store_id', $store->id)->delete();
            
            // delete store products
            $products = Product::where('store_id', $store->id)->get();
            $productIds = $products->pluck('id')->toArray();
    
            $productCategories = ProductCategory::whereIn('product_id', $productIds)->delete();    
            $productTags = ProductTag::whereIn('product_id', $productIds)->delete();    
            $productImages = ProductImage::whereIn('product_id', $productIds)->delete();    
            $productVideos = ProductVideo::whereIn('product_id', $productIds)->delete();    
            $productSeoSettings = ProductSeoSetting::whereIn('product_id', $productIds)->delete();    
            $productTweets = ProductTweet::whereIn('product_id', $productIds)->delete();    
            $products = Product::whereIn('id', $productIds)->delete();
    
            // delete store categories
            $categories = Category::where('store_id', $store->id)->delete();
            // delete store tags
            $tags = Tag::where('store_id', $store->id)->delete();
            
            if(File::exists($directory)) 
                File::deleteDirectory($directory);
    
            $store->delete();
            
            Session::flash("success", $store->name . " successfully deleted");
        }

        return redirect()->route('listStore');
    }

    public function uploadLogo(Request $request, $subdomain){
        $file = $request->file('logo');
        $fileExtension = $file->getClientOriginalExtension();
        $fileName = 'IMG_' . time() . '.' . $fileExtension;

        $file->move('img/uploads/' . $subdomain . '/logo', $fileName);

        return $fileName;
    }

    public function setupSettings($store_id){

        $amazon = [
            'market_place' => 'webservices.amazon.com',
            'associate_tag' => '',
            'access_key_id' => '',
            'secret_access_key' => ''
        ];

        $ebay = [
            'application_id' => '',
            'network_id' => '',
            'tracking_id' => '',
            'custom_id' => ''
        ];

        $aliexpress = [
            'key' => '',
            'deep_link_hash' => ''
        ];

        $walmart = [
            'key' => '',
        ];

        $shopcom = [
            'publisher_id' => '',
            'api_key' => '',
        ];

        $cjcom = [
            'website_id' => '',
            'api_key' => '',
        ];

        $jvzoo = [
            'affiliate_id' => '',
        ];

        $clickbank = [
            'account_name' => '',
        ];

        $warriorplus = [
            'affiliate_id' => '',
        ];

        $paydotcom = [
            'affiliate_id' => '',
        ];


        $alliates_settings = [
            'amazon' => $amazon,
            'ebay' => $ebay,
            'aliexpress' => $aliexpress,
            'walmart' => $walmart,
            'shopcom' => $shopcom,
            'cjcom' => $cjcom,
            'jvzoo' => $jvzoo,
            'clickbank' => $clickbank,
            'warriorplus' => $warriorplus,
            'paydotcom' => $paydotcom,
        ]; 
        
        foreach ($alliates_settings as $key => $value) {
            AffiliateSetting::create([
                'store_id' => $store_id,
                'name' => $key,
                'settings' => json_encode($value)
            ]);
        }

        $facebook = [
            'application_id' => '',
            'application_secret' => '',
        ];
        
        $twitter = [
            'consumer_key' => '',
            'consumer_secret' => '',
            'access_token' => '',
            'access_token_secret' => ''
        ];

        $instagram = [
            'username' => '',
            'password' => '',
            'client_id' => '',
            'client_secret' => '',
        ];

        $tumblr = [
            'consumer_key' => '',
            'consumer_secret' => '',
            'oauth_token' => '',
            'oauth_secret' => '',
            'blog_name' => '',
        ];

        $pinterest = [
            'application_id' => '',
            'application_secret' => '',
            'board_name' => '',
        ];

        $social_settings = [
            'facebook' => $facebook,
            'twitter' => $twitter,
            'instagram' => $instagram,
            'tumblr' => $tumblr,
            'pinterest' => $pinterest
        ]; 

        $autoresponders = [
            'mailchimp' => [
                'api_key' => '',
                'list_id' => '',
            ],
            'aweber' => [
                'consumer_key' => '',
                'consumer_secret' => '',
                'access_token' => '',
                'access_secret' => '',
                'account_id' => '',
                'list_id' => '',
            ],
            // 'infusionsoft' => '',
            'markethero' => [
                'api_key' => '',
                'tag_id' => '',
            ],
        ];

        foreach ($social_settings as $key => $value) {
            SocialSetting::create([
                'store_id' => $store_id,
                'name' => $key,
                'settings' => json_encode($value)
            ]);
        }

        $smo_settings = ['facebook', 'twitter', 'linkedIn', 'google', 'pinterest']; 
        foreach ($smo_settings as $smo_setting) {
            SmoSetting::create([
                'store_id' => $store_id,
                'name' => $smo_setting,
                'page_url' => '',
                'design_options' => 1,
                'display_options' => 0
            ]);
        }

        $getResponseAPI = [
            'api_key' => '',
            'campaign_name' => '',
            'campaign_id' => '',
        ]; 

        GetResponseSetting::create([
            'store_id' => $store_id,
            'settings' => json_encode($getResponseAPI)
        ]);

        foreach ($autoresponders as $key => $autoresponder) {
            Autoresponder::create([
                'store_id' => $store_id,
                'name' => $key,
                'settings' => json_encode($autoresponder),
            ]);
        }
    }

    public function setupBusinessProfile($store_id){
        ContactUsProfile::create([
            'store_id' => $store_id
        ]);
    }
    
    public function setupFacebookCustomerChat($store_id){
        FacebookCustomerChat::create([
            'store_id' => $store_id
        ]);
    }

    public function setupFacebookCommentPlugin($store_id){
        FacebookCommentPlugin::create([
            'store_id' => $store_id
        ]);
    }
    
    public function getDomain(){
        $domain = $_SERVER['HTTP_HOST'];
        // $domain = explode('.', $domain, 2);
        // $domain = $domain[1];

        return $domain;
    }

    public function storeOwnDomain(Request $request){
        return view('hosttoowndomain');
    }
}
