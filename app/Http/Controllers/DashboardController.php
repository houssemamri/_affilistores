<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Session;
use Auth;
use Route;
use Crypt;
use Response;
use Cookie;
use Mail;
use DateTime;
use DateInterval;
use Carbon\Carbon;
use App\Product;
use App\Store;
use App\Category;
use App\ProductCategory;
use App\StoreCategoryMenu;
use App\StoreSlider;
use App\MemberNotification;
use App\MemberNotificationView;
use App\Poll;
use App\PollVote;
use App\Page;
use App\Setup;
use App\Article;
use App\ContactUsMessage;
use App\ExitPop;
use App\ProductHit;
use App\Membership;
use App\ProductReview;
use App\Subscriber;
use App\Blog;
use App\SocialProof;
use App\AccessFeature;
use App\Theme;
use App\ColorScheme;
use App\AffiliateSetting;
use App\GetResponseSetting;
use App\StoreBannerAd;
use App\ProductCountdown;
use App\Autoresponder;

class DashboardController extends GlobalController
{
    private $store;
    private $socialProofs;
    private $theme;
    private $colorScheme;
    private $features;

    public function __construct(){
        parent::__construct();
        
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();
        $this->socialProofs = $this->socialProofs();
        $this->theme = isset($this->store) ? $this->store->storeTheme->theme->slug : '';
        $this->colorScheme =  isset($this->store) ? $this->store->storeTheme->colorScheme->slug: '';
        $this->features = $this->getFeatures();
    }

    public function index(){
        if(Auth::user()){
            $this->checkBanner();
            $store = $this->store;
            $welcomeMessage = Setup::where('key', 'welcome_message')->first();
            $poll = Poll::where('status', 1)->orderBy('id', 'DESC')->get()->first();
            $pollVoted = isset($poll) ? PollVote::where('user_id', Auth::user()->id)->where('poll_id', $poll->id)->count() : 0;
            $stores = Store::where('user_id', Auth::user()->id)->get();
            $articles = Article::get()->take(5);
            $messages = $this->store->contactMessages()->orderBy('created_at', 'DESC')->get()->take(5);
            $statistics = $this->getProductStatistics();
            $countStoreThisMonth = Store::where('user_id', Auth::user()->id)->whereMonth('created_at', date('m'))->count();
            $limit = Auth::user()->memberDetail->membership->stores_per_month;
            $featuredStores = Store::where('featured', 1)->get();

            return view('dashboard', compact('store', 'welcomeMessage', 'poll', 'pollVoted', 'stores', 'articles', 'statistics', 'messages', 'countStoreThisMonth', 'limit', 'featuredStores'));
        }

        return redirect()->route('logout');
    }

    public function checkBanner(){
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
        ];

        foreach ($settings as $key => $value) {
            $existing = $this->store->bannerAd->where('type', $key);
            
            if(isset($existing) && count($existing) == 0){
                StoreBannerAd::create([
                    'store_id' => $this->store->id,
                    'type' =>  $key,
                    'content' => json_encode($value)
                ]);
            }
        }
    }

    public function main(Request $request){
        if(isset($request->preview) && $request->preview == true)
            $this->preview($request);

        $store = $this->store;
        $features = $this->features;
        $categories = Category::where('store_id', $this->store->id)->get();
        $exitpop = ExitPop::where('store_id', $this->store->id)->where('status', 1)->first();
        $sliders = $this->getSliders();
        $bannerAd = $store->bannerAd->where('selected', 1)->where('type', '<>', 'MenuBanner')->first();
        $view = 'index.' . $this->theme . '.index';
        $scheme = $this->colorScheme;
        $blogs = Blog::where('store_id', $this->store->id)->where('published', 1)->get();
        
        return view($view, compact('store', 'scheme', 'sliders', 'categories', 'exitpop', 'bannerAd', 'blogs', 'features'));
    }

    public function category(Request $request, $subdomain, $permalink){
        if(isset($request->preview) && $request->preview == true)
            $this->preview($request);

        $store = $this->store;
        $features = $this->features;
        $category = Category::where('store_id', $store->id)->where('permalink', $permalink)->first();
        $scheme = $this->colorScheme;
        
        if(isset($category)){
            $products = ProductCategory::where('category_id', $category->id)->paginate(16);
            $exitpop = ExitPop::where('store_id', $this->store->id)->where('status', 1)->first();
            $sliders = $this->getSliders();
            $metaTitle = $this->getCategoryMetaTitle($category->name) !== "" ? $this->getCategoryMetaTitle($category->name) : $category->name;
            
            $view = 'index.' . $this->theme . '.categories';
    
            return view($view, compact('category', 'scheme', 'store', 'sliders', 'products', 'exitpop', 'metaTitle', 'features'));
        }else{
            return abort(404);
        }
        
    }

    public function product(Request $request, $subdomain, $permalink){
        if(isset($request->preview) && $request->preview == true)
            $this->preview($request);

        $store = $this->store;
        $features = $this->features;
        $product = Product::where('store_id', $store->id)->where('permalink', $permalink)->first();

        if(isset($product)){
            $exitpop = ExitPop::where('store_id', $this->store->id)->where('status', 1)->first();
            $metaTitle = $this->getProductMetaTitle($product) == "" ? $product->name : $this->getProductMetaTitle($product);
            $sliders = $this->getSliders();
            $relatedProducts = $this->getRelated($product);
            $bannerAd = $store->bannerAd->where('selected', 1)->where('type', '<>', 'MenuBanner')->first();
            $view = 'index.' . $this->theme . '.products';
            $scheme = $this->colorScheme;
            $addToCartLink = $this->getAddToCart($product);
            $countdownTimer = ProductCountdown::where('product_id', $product->id)->orderBy('id', 'DESC')->first();

            return view($view, compact('metaTitle', 'store', 'addToCartLink', 'scheme', 'sliders', 'product', 'relatedProducts', 'exitpop', 'bannerAd', 'features', 'countdownTimer'));
        }else{
            return abort(404);
        }
     }

    public function search(Request $request, $subdomain){
        if(isset($request->preview) && $request->preview == true)
            $this->preview($request);

        $store = $this->store;
        $features = $this->features;
        $keyword = $request->keyword;
        $exitpop = ExitPop::where('store_id', $this->store->id)->where('status', 1)->first();
        $metaTitle = $this->getProductSearchMetaTitle($keyword);
        $view = 'index.' . $this->theme . '.search';
        $scheme = $this->colorScheme;

        if(isset($keyword) && $keyword !== ''){

            $products = \DB::table('products')
                        ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
                        ->join('categories', 'categories.id', '=', 'product_categories.category_id')
                        ->where('products.store_id', $store->id)
                        ->where('products.status', 1)
                        ->where(function($query) use ($keyword) {
                            $query->orWhere('products.name', 'LIKE', '%' . $keyword . '%')
                                ->orWhere('products.description', 'LIKE', '%' . $keyword . '%')
                                ->orWhere('categories.name', 'LIKE', '%' . $keyword . '%')
                                ->orWhere('categories.description', 'LIKE', '%' . $keyword . '%');
                        })
                        ->select('products.*')
                        ->paginate(20);

            $products->appends(['keyword' => $keyword]);
        }else
            $products = [];

        return view($view, compact('products', 'scheme', 'store', 'keyword', 'exitpop', 'metaTitle', 'features'));
    }

    public function customerService(Request $request, $subdomain, $policy){
        if(isset($request->preview) && $request->preview == true)
            $this->preview($request);

        $store = $this->store;
        $features = $this->features;
        $view = 'index.' . $this->theme . '.customer-service';
        $scheme = $this->colorScheme;
        $customerService = $this->store->legalPage;
        
        return view($view, compact('customerService', 'scheme', 'policy', 'store', 'features'));
    }

    public function getCategoryMetaTitle($category){
        $meta_title = $this->store->archivePage->meta_title;
        $meta_title = str_replace('%site_title%', $this->store->name, $meta_title);
        $meta_title = str_replace('%archive_title%', $category, $meta_title);

        return $meta_title;
    }

    public function getProductMetaTitle($product){
        $meta_title = $this->store->productPage->meta_title;
        $meta_title = str_replace('%site_title%', $this->store->name, $meta_title);
        $meta_title = str_replace('%product_title%', isset($product->seoSettings->meta_title) ? $product->seoSettings->meta_title : $product->name , $meta_title);

        return $meta_title;
    }

    public function getProductSearchMetaTitle($keyword){
        $meta_title = $this->store->titleSettings->search_page_title;
        $meta_title = str_replace('%site_title%', $this->store->name, $meta_title);
        $meta_title = str_replace('%search_title%', $keyword, $meta_title);

        return $meta_title;
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
        $productFeeds->link = route('redirectDashboard', $this->store->subdomain);
        $productFeeds->setDateFormat('datetime'); // 'datetime', 'timestamp' or 'carbon'
        $productFeeds->pubdate = $this->store->created_at; // date of latest news
        $productFeeds->lang = 'en';
        $productFeeds->setShortening(true); // true or false
        $productFeeds->setTextLimit(500); // maximum length of description text

        foreach ($products as $product)
        {
            $product = !isset($categoryId) ? $product : $product->product;
            // set item's title, author, url, pubdate, description and content
            $productFeeds ->add(
                $product->name,
                route('product.show', ['subdomain' => $this->store->id, 'permalink' => $product->permalink]),
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

    public function getSliders(){
        $sliders = StoreSlider::where('store_id', $this->store->id)->get();
        $enabledSliders = [];
        
        foreach ($sliders as $slider) {
            if($slider->slider->status == 1)    {
                array_push($enabledSliders, $slider);
            }
        }

        return $enabledSliders;
    }

    public function getRelated($product){
        $productCategories = ProductCategory::where('product_id', $product->id)->pluck('category_id')->toArray();
        $relatedProducts = ProductCategory::whereIn('category_id', $productCategories)->where('product_id', '<>', $product->id)->inRandomOrder()->get()->take(4);   

        return $relatedProducts;
    }

    public function getAddToCart($product){
        if($product->source == 'amazon'){
            $amazon = AffiliateSetting::where('store_id', $this->store->id)->where('name', 'amazon')->first();
            $settings = json_decode($amazon->settings);

            if((isset($settings->access_key_id) && !empty($settings->access_key_id)) && (isset($settings->associate_tag) && !empty($settings->associate_tag))){
                $marketPlace = [
                    'webservices.amazon.com.au' => 'https://www.amazon.com.au/gp/aws/cart/add.html',
                    'webservices.amazon.com.br' => 'https://www.amazon.com.br/gp/aws/cart/add.html',
                    'webservices.amazon.ca' => 'https://www.amazon.ca/gp/aws/cart/add.html',
                    'webservices.amazon.cn' => 'https://www.amazon.cn/gp/aws/cart/add.html',
                    'webservices.amazon.fr' => 'https://www.amazon.fr/gp/aws/cart/add.html',
                    'webservices.amazon.de' => 'https://www.amazon.de/gp/aws/cart/add.html',
                    'webservices.amazon.in' => 'https://www.amazon.in/gp/aws/cart/add.html',
                    'webservices.amazon.it' => 'https://www.amazon.it/gp/aws/cart/add.html',
                    'webservices.amazon.co.jp' => 'https://www.amazon.co.jp/gp/aws/cart/add.html',
                    'webservices.amazon.com.mx' => 'https://www.amazon.com.mx/gp/aws/cart/add.html',
                    'webservices.amazon.es' => 'https://www.amazon.es/gp/aws/cart/add.html',
                    'webservices.amazon.co.uk' => 'https://www.amazon.co.uk/gp/aws/cart/add.html',
                    'webservices.amazon.com' => 'https://www.amazon.com/gp/aws/cart/add.html',
                ];
    
    
                return $marketPlace[$settings->market_place] . '?AWSAccessKeyId='. $settings->access_key_id .'&AssociateTag='.$settings->associate_tag.'&ASIN.1='. $product->reference_id .'&Quantity.1=1&add=add';
            }else{
                return $product->details_link;
            }
        }
    }

    public function pages($subdomain, $slug){
        $store = $this->store;
        $page = Page::where('slug', $slug)->first();
        
        if(!isset($page)){
            Session::flash('error', 'Page not found');
            return redirect()->route('dashboard', $store->subdomain);
        }else{
            $page_availability = $page->available->where('membership_id', Auth::user()->memberDetail->membership_id)->count();
            
            if($page_availability == 0){
                Session::flash('error', 'Unauthorize access of page!');
                return redirect()->route('dashboard', $store->subdomain);
            }
        }

        return view('pages.index', compact('page', 'store'));
    }

    public function articles(){
        $articles = Article::all();

        return view('articles.index', compact('articles', 'store'));
    }

    public function articlesRead($subdomain, $id){
        $decrypted = Crypt::decrypt($id);
        $store = $this->store;
        $article = Article::find($decrypted);
        
        if(!isset($article)){
            Session::flash('error', 'Article not found');
            return redirect()->route('dashboard', $store->subdomain);
        }

        return view('articles.show', compact('article', 'store'));
    }

    public function notifications(){
        $notifications = MemberNotification::paginate(15);
        $openedNotifications = MemberNotificationView::where('user_id', Auth::user()->id)->where('is_open', 1)->pluck('member_notification_id')->toArray();

        return view('notifications.index', compact('notifications', 'openedNotifications'));
    }

    public function notificationsOpen($id){
        if(Auth::user()){
            $decrypted = Crypt::decrypt($id);
            $notification = MemberNotification::find($decrypted);
    
            if(isset($notification)){
                $isAlreadyOpen = MemberNotificationView::where('user_id', Auth::user()->id)->where('member_notification_id', $notification->id)->first();
                if(!isset($isAlreadyOpen)){
                    MemberNotificationView::create([
                        'member_notification_id' => $notification->id,
                        'user_id' => Auth::user()->id,
                        'is_open' => 1
                    ]);
                }
            }
    
            return view('notifications.show', compact('notification'));
        }
        
        return redirect()->route('logout');
    }

    public function vote($subdomain, $pollId, $pollOption){
        if(Auth::user()){
            $decrypted = Crypt::decrypt($pollId);
            $pollOption = Crypt::decrypt($pollOption);
            $poll = Poll::find($decrypted);
            $isAlreadyVote = PollVote::where('user_id', Auth::user()->id)->where('poll_id', $decrypted)->first();
            $options = [];

            if(!isset($isAlreadyVote)){

                PollVote::create([
                    'poll_id' => $poll->id,
                    'poll_option_id' => $pollOption,
                    'user_id' => Auth::user()->id
                ]);

                $poll->update([
                    'total_vote' => $poll->total_vote + 1
                ]);
            }

            foreach ($poll->options as $option) {
                array_push($options, [
                    'name' => $option->name,
                    'total' => PollVote::where('poll_option_id', $option->id)->count()
                ]);
            }

            // $option = PollVote::where('poll_option_id', $pollOption)->where('poll_id', $decrypted)->count();
            return Response::json(['success' => true, 'options' => $options, 'total' => $poll->total_vote]);
        }

        return redirect()->route('logout');
    }

    public function sendMessage(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);

        ContactUsMessage::create([
            'store_id' => $this->store->id,
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        Session::flash('success', 'Your message is successfully sent! Thank you.');
        return redirect()->back();
    }

    public function exitPopSendEmail(Request $request){
        $exitpop = ExitPop::where('store_id', $this->store->id)->where('status', 1)->first();
        $store = $this->store;

        $this->validate($request, [
            'email' => 'required',
        ]);

        $send = Mail::send([], [], function ($message) use ($exitpop, $store, $request) {
            $message->from(env('MAIL_USERNAME'), ucwords($store->name));
            $message->to($request->email);
            $message->subject($exitpop->heading);
            $message->setBody(htmlspecialchars_decode($exitpop->content), 'text/html');
        });

        $this->executeSubscribe($request->email);

        Cookie::queue('sent_email_exit_pop', true, 120);
        return redirect()->back();
    }

    public function getProductStatistics(){
        $today = new DateTime(); 
        $startDay = new DateTime(); 
        $startDay->sub(new DateInterval('P7D'));
        $pastSevenDays = $startDay;
        $postedReviews = 0;

        $postedToday = Product::where('store_id', $this->store->id)->where('status', 1)->whereDate('published_date', $today->format('Y-m-d'))->count();
        $totalPosted = Product::where('store_id', $this->store->id)->where('status', 1)->count();
        $weeklyHits = ProductHit::where('store_id', $this->store->id)->where('page_hits', 1)->whereDate('created_at', '>=', $pastSevenDays->format('Y-m-d'))->whereDate('created_at', '<=', $today->format('Y-m-d'))->count();
        $weeklyAffiliateHits = ProductHit::where('store_id', $this->store->id)->where('affiliate_hits', 1)->whereDate('created_at', '>=', $pastSevenDays->format('Y-m-d'))->whereDate('created_at', '<=', $today->format('Y-m-d'))->count();
        $products = Product::where('store_id', $this->store->id)->where('status', 1)->get();

        foreach ($products as $product) {
            $postedReviews = $postedReviews + $product->reviews->where('approved', 1)->count();
        }

        $statistics = [
            'postedToday' => $postedToday,
            'postedReviews' => $postedReviews,
            'totalPosted' => $totalPosted,
            'weeklyHits' => $weeklyHits,
            'weeklyAffiliateHits' => $weeklyAffiliateHits,

        ];

        return $statistics;
    }

    public function memberships(){
        $membership = Membership::where('id', Auth::user()->memberDetail->membership_id)->first();
        $nextMembership = Membership::where('id', $membership->next_upgrade_membership_id)->first();
        return view('pages.memberships', compact('membership', 'nextMembership'));
    }

    public function review(Request $request){
        if($request->isMethod('POST')){
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required',
                'ratings' => 'required',
                'review' => 'required', 
                'g-recaptcha-response' => 'required|captcha',
            ],
            [
                'email.required' => "The email field is required.",
                'password.required' => "The password field is required.",
                'ratings.required' => "The ratings field is required.",
                'review.required' => "The review field is required.",
                'g-recaptcha-response.required' => "Please verify that you are not a robot.",
                'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
            ]);

            $product = Product::where('permalink', $request->permalink)->first();

            //add status in reviews
            $review = ProductReview::create([
                'product_id' => $product->id,
                'name' => $request->name,
                'email' => $request->email,
                'ratings' => $request->ratings,
                'review' => $request->review,
                'approved' => $product->auto_approve == 1 ? 1 : 0
            ]);

            if($review->approved == 1){
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

            Session::flash('success', 'Review successfully sent. Wait for your review to be approved.');
            return redirect()->back();
        }
    }

    public function subscribe(Request $request){
        if($request->isMethod('POST')){
            $this->validate($request, [
                'email' => 'required',
            ]);

            $settings = $this->store->getResponseSetting;
            $subscribed = Subscriber::where('store_id', $this->store->id)->where('email', $request->email)->count();

            if($subscribed == 0){
                $subscriber = Subscriber::create([
                    'store_id' => $this->store->id,
                    'email' => $request->email,
                ]);

                if($this->validCredentials($settings)){
                    $addEmail = $this->addEmailtoCampaign($subscriber->email, json_decode($settings->settings));
                }

                $addEmailMailChimp = $this->addEmailtoMailchimp($subscriber->email);
                $addEmailToMarketHero = $this->addEmailToMarketHero($subscriber->email);
                $addEmailToAweber = $this->addEmailToAweber($subscriber->email);
                
                Session::flash('success', 'Thank you for subscribing to our newsletter');
            }else{
                Session::flash('error', 'Email already subscribed to our newsletter');
            }
    
            return redirect()->back();
        }
    }

    public function executeSubscribe($email){
        $settings = $this->store->getResponseSetting;
        $subscribed = Subscriber::where('store_id', $this->store->id)->where('email', $email)->count();

        if($subscribed == 0){
            $subscriber = Subscriber::create([
                'store_id' => $this->store->id,
                'email' => $email,
            ]);

            if($this->validCredentials($settings)){
                $addEmail = $this->addEmailtoCampaign($subscriber->email, json_decode($settings->settings));
            }

            $addEmailMailChimp = $this->addEmailtoMailchimp($subscriber->email);
            $addEmailToMarketHero = $this->addEmailToMarketHero($subscriber->email);
            $addEmailToAweber = $this->addEmailToAweber($subscriber->email);
        }
    }

    public function validCredentials($settings){
        $settings = json_decode($settings->settings);
        foreach ($settings as $setting) {
            if($setting == ""){
                return false;
                break;
            }
        }

        return true;
    }

    public function addEmailtoCampaign($email, $settings){
        try{
            $client = new Client();

            $response = $client->request('POST', 'https://api.getresponse.com/v3/contacts', [
                'headers' => [
                    'X-Auth-Token' => 'api-key ' . trim($settings->api_key),
                    'Content-Type'     => 'application/json',
                ], 
                'json' => [
                    'email' => $email,
                    'campaign' => [
                        'campaignId' => $settings->campaign_id
                    ]
                ]
            ]);
            
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function addEmailtoMailchimp($email){
        try{
            $settings = Autoresponder::where('store_id', $this->store->id)->where('name', 'mailchimp')->first();

            if($this->validCredentials($settings)){
                $data = json_decode($settings->settings);
                $this->addMailChimpSubscriber($data->api_key, $data->list_id, $email);
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function addMailChimpSubscriber($apikey, $listId, $email){
        try{
            $client = new Client(['auth' => ['anystring', $apikey]]);
            $response = $client->request('POST', 
                'https://' . $this->getMailChimpServer($apikey) . '.api.mailchimp.com/3.0/lists/' . $listId . '/members',
                    ['body' => json_encode([
                        'email_address' => $email,
                        'status' => 'subscribed',
                    ])
                ]
            );
           
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e){
            return [];
        }
    }

    public function getMailChimpServer($apikey){
        return explode('-', $apikey)[1];
    }

    public function addEmailToMarketHero($email){
        try{
            $settings = Autoresponder::where('store_id', $this->store->id)->where('name', 'markethero')->first();
            if($this->validCredentials($settings)){
                $data = json_decode($settings->settings);

                $client = new Client();

                $response = $client->post('http://api.markethero.io/v1/api/tag-lead', [
                    'json' => [
                        'apiKey' => $data->api_key,
                        'email' => $email
                    ]
                ]);

            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function addEmailToAweber($email){
        try{
            $settings = Autoresponder::where('store_id', $this->store->id)->where('name', 'aweber')->first();

            if($this->validCredentials($settings)){
                $data = json_decode($settings->settings);

                $stack = HandlerStack::create();

                $middleware = new Oauth1([
                    'consumer_key'    => $data->consumer_key,
                    'consumer_secret' => $data->consumer_secret,
                    'token'           => $data->access_token,
                    'token_secret'    => $data->access_secret,
                ]);

                $stack->push($middleware);

                $client = new Client([
                    'handler' => $stack
                ]);

                $url = 'https://api.aweber.com/1.0/accounts/' . $data->account_id . '/lists/' . $data->list_id . '/subscribers?ws.op=create';

                $response = $client->post($url, [
                    'auth' => 'oauth',
                    'content-type' => 'application/json',
                    'json' => [
                        'email' => $email
                    ]
                ]);
    
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function blogsAll(){
        $store = $this->store;
        $features = $this->features;
        $view = 'index.' . $this->theme . '.blog-list';
        $scheme = $this->colorScheme;
        $blogs = Blog::where('store_id', $this->store->id)->where('published', 1)->orderBy('id', 'DESC')->paginate(4);
        
        return view($view, compact('customerService', 'scheme', 'blogs', 'store', 'features'));
    }

    public function blogRssFeed(){
        $blogFeeds = \App::make("feed");
        
        // creating rss feed with our most recent 20 records in news table
        $blogs = Blog::where('store_id', $this->store->id)->take(20)->get();
                        
        // set your feed's title, description, link, pubdate and language
        $blogFeeds->title = $this->store->name;
        $blogFeeds->link = route('index', $this->store->subdomain);
        $blogFeeds->setDateFormat('datetime'); // 'datetime', 'timestamp' or 'carbon'
        $blogFeeds->pubdate = $this->store->created_at; // date of latest news
        $blogFeeds->lang = 'en';
        $blogFeeds->setShortening(true); // true or false
        $blogFeeds->setTextLimit(500); // maximum length of description text

        foreach ($blogs as $blog){
            // set item's title, url, description, price, 'image', pubdate
            $blogFeeds ->add(
                $blog->title,
                route('index.blog.show', ['subdomain' => $this->store->subdomain, 'id' => $blog->id]),
                $blog->post,
                '',
                '',
                $blog->created_at
            );
        }

        // return your feed ('atom' or 'rss' format)
        return $blogFeeds->render('atom')->withHeaders([
            'Content-Type' => 'text/xml'
        ]);
    }

    public function blogShow($subdomain, $id){
        $blog = Blog::find($id);

        return $blog;
    }

    public function blogView($subdomain, $slug){
        $store = $this->store;
        $features = $this->features;
        $view = 'index.' . $this->theme . '.view-blog';
        $scheme = $this->colorScheme;
        $blog = Blog::where('store_id', $store->id)->where('slug', $slug)->first();
        $blogProducts = $this->getBlogProducts($blog);
        return view($view, compact('scheme', 'blog', 'store', 'features', 'blogProducts'));
    }

    public function getBlogProducts($blog){
        return ProductCategory::where('category_id', $blog->category_id)->inRandomOrder()->get()->take(4);   
    }

    public function socialProofs(){
        if(isset($this->store)){
            $socialProofs = $this->store->socialProofs()->where('active', 1)->orderBy('order', 'ASC')->get();
            $proofs = [];
    
            foreach ($socialProofs as $socialProof) {
                array_push($proofs, [
                    'data' => $socialProof->content,
                    'type' =>  $socialProof->type
                ]);
            }
            if(json_decode($this->store->socialProofSetting->settings)->display_order == 'random'){
                shuffle($proofs);
            }
    
            return response()->json($proofs);
        }
        
    }

    public function getFeatures(){
        $features = [];
        if(isset($this->store)){
            $accessFeatures = AccessFeature::where('membership_id', $this->store->user->memberDetail->membership->id)->get();
        
            foreach ($accessFeatures as $feature) {
                if($feature->features->type !== 'affiliate_store')
                    array_push($features, strtolower(str_replace('.', '', $feature->features->name)));
            }
        }
        return $features;
    }

    public function preview($request){
        if(isset($request->theme) && isset($request->scheme)){
            $theme = $this->store->storeTheme->theme->where('id', $request->theme)->first();
            $colorScheme = $this->store->storeTheme->colorScheme->where('id', $request->scheme)->first();

            if(isset($theme) && isset($colorScheme)){
                $this->theme = $theme->slug;
                $this->colorScheme = $colorScheme->slug;
            }
        }
    }
}
