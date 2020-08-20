<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\GlobalController;
use App\Http\Controllers\StoreController;
use GuzzleHttp\Client;
use Input;
use Hash;
use Session;
use Crypt;
use Mail;
use Auth;
use File;
use DateTime;
use DateInterval;
use App\EmailResponder;
use App\Setup;
use App\User;
use App\UserDetail;
use App\MemberDetail;
use App\Membership;
use App\Audit;
use App\Ipn;
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
use App\Slider;
use App\SeoSettingsHomePage;
use App\SeoSettingsArchivePage;
use App\SeoSettingsProductPage;
use App\SeoSettingsTitleSetting;
use App\SeoSettingsWebmastersSetting;
use App\SeoSettingsAnalytic;
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
use App\MemberNotificationView;
use App\PollVote;
use App\ColorScheme;
use App\StoreSocialProofSetting;

class MemberController extends GlobalController
{
    public function index(){
        $members = User::where('role_id', 3)->with([
            'memberDetail.membership'
        ])->get();

        // $memberships = Membership::pluck('title', 'id')->toArray();

        return view('admin.members.index', compact('members'));
    }

    public function create(Request $request){
        $memberships = Membership::all();

        if($request->isMethod('POST')){
            $this->validate($request, [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required',
                'confirm_password' => 'required',
            ]);

            if($request->password == $request->confirm_password){
                $unique = UserDetail::where('first_name', $request->first_name)->where('last_name', $request->last_name)->count();
                if($unique == 0){
                    $input = Input::all();

                    $user = User::create([
                        'name' => $request->first_name.' '.$request->last_name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'role_id' => 3,
                        'active' => 1
                    ]);
    
                    $user_details = UserDetail::create([
                        'user_id' => $user->id,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                    ]);

                    $member_details = MemberDetail::create([
                        'user_id' => $user->id,
                        'membership_id' => $request->membership,
                        'expiry_date' => isset($request->expiry_date) ? $request->expiry_date : null
                    ]);

                    if($this->validCredentials()){
                        $this->addEmailtoCampaign($user->email);
                    }
                    
                    $lastId = Store::orderBy('id', 'DESC')->first();
                    
                    $storeCount = isset($lastId) ? ($lastId->id + 1) : 1;
                    $storeName = 'store'. $storeCount;

                    $firstStore = $this->createFirstStore($user->id, $storeName, $storeName);

                    Audit::create([
                        'type' => 'signup',
                        'action' => 'register a new member',
                        'user_id' => $user->id
                    ]);
                    
                    //send emails to admin, member, and autoresponders
                    $sendMail = $this->sendMails($user, $request->password);

                    Session::flash('success', 'Successfully added member '.$user->name);
                    return redirect()->route('members.index');
                }else{
                    Session::flash('error', 'First Name and Last Name already existing.');
                    return redirect()->back()->withInput(Input::all());
                }
            }else{
                Session::flash('error', 'Password did not match');
                return redirect()->back()->withInput(Input::all());
            }
        }

        return view('admin.members.create', compact('memberships'));
    }

    public function validCredentials(){
        $settings = Setup::whereIn('id', ['6', '7', '11'])->get();

        foreach ($settings as $setting) {
            if($setting->value == ""){
                return false;
                break;
            }
        }

        return true;
    }

    public function addEmailtoCampaign($email){
        $settings = Setup::whereIn('id', ['6', '7', '11'])->get();

        try{
            $client = new Client();

            $response = $client->request('POST', 'https://api.getresponse.com/v3/contacts', [
                'headers' => [
                    'X-Auth-Token' => 'api-key ' . trim($settings->where('key', 'api_key')->first()->value),
                    'Content-Type'     => 'application/json',
                ], 
                'json' => [
                    'email' => $email,
                    'campaign' => [
                        'campaignId' => $settings->where('key', 'campaign_id')->first()->value
                    ]
                ]
            ]);
            
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function createFirstStore($userId, $storeName, $subdomain){
        $store = Store::create([
            'user_id' => $userId,
            'name' => $storeName,
            'subdomain' => strtolower($subdomain),
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

        //setup directory 
        if(!File::exists('img/uploads/'.$store->subdomain)) 
            mkdir('img/uploads/'.$store->subdomain, 0777, true);
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
        }catch(\Exception $e){
            $privacy = '';
            $termsCondition = '';
        }

        StoreLegalPage::create([
            'store_id' => $storeId,
            'terms_conditions' => $privacy,
            'privacy_policy' => $termsCondition,
            'contact_us' => '',
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

        $alliates_settings = [
            'amazon' => $amazon,
            'ebay' => $ebay,
            'aliexpress' => $aliexpress,
            'walmart' => $walmart,
            'shopcom' => $shopcom,
            'cjcom' => $cjcom
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

        $social_settings = [
            'facebook' => $facebook,
            'twitter' => $twitter,
            'instagram' => $instagram
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

    public function setMembershipExpiration($membership){
        $exiration_date = new DateTime();
        
        if($membership->trial_period !== null){
            $exiration_date->add(new DateInterval('P'.$membership->trial_period.'D'));
        }else{
            $exiration_date->add(new DateInterval('P'.$membership->frequency.'M'));
        }
       
        return $exiration_date;
    }

    public function edit(Request $request, $id){
        $memberships = Membership::all();
        $decrypted = Crypt::decrypt($id);
        $user = User::find($decrypted);

        if($request->isMethod('POST')){
            if(isset($_POST['update_password'])){
                $this->validate($request, [
                    'new_password' => 'required',
                    'confirm_password' => 'required',
                ]);
        
                if($request->new_password == $request->confirm_password){
                    
                    $user->password = Hash::make($request->new_password);
                    $user->save();

                    Session::flash('success', $user->detail->first_name . '\'s password successfully updated!');
                }else{
                    Session::flash('error', 'Password did not match');
                }

                return redirect()->route('members.index');
            }else{
                $this->validate($request, [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => 'required|unique:users,email,' . $user->id,
                ]);

                $unique = UserDetail::where('first_name', $request->first_name)->where('last_name', $request->last_name)->where('user_id', '<>', $user->id)->count();
                if($unique == 0){
                    $input = Input::all();

                    $user->update([
                        'name' => $request->first_name.' '.$request->last_name,
                        'email' => $request->email,
                        'active' => 1
                    ]);

                    $user->detail->update([
                        'user_id' => $user->id,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                    ]);

                    $membership = Membership::find($request->membership);
                    // $expiration = $this->setMembershipExpiration($membership);

                    $user->memberDetail->update([
                        'user_id' => $user->id,
                        'membership_id' => $request->membership,
                        'expiry_date' => isset($request->never_expire) ? null : $request->expiry_date
                    ]);

                    Session::flash('success', 'Successfully added member '.$user->name);
                    return redirect()->route('members.index');
                }else{
                    Session::flash('error', 'First Name and Last Name already existing.');
                    return redirect()->back()->withInput(Input::all());
                }
            }
        }

        return view('admin.members.edit', compact('id', 'user', 'memberships'));
    }

    public function changeStatus(Request $request, $id, $status){
        $decrypted = Crypt::decrypt($id);
        $user = User::find($decrypted);

        $status = ($status == 'activate') ? 1 : 0;
        $action = ($status == 'activate') ? 'activated' : 'suspended';

        $user->update([
            'active' => $status
        ]);
        
        Session::flash('success', 'Successfully ' .$action.' '.$user->name);
        return redirect()->route('members.index');
    }

    public function delete(Request $request){
        if(isset($request->confirmation) && $request->confirmation === "DELETE"){
            $decrypted = Crypt::decrypt($request->member_id);
            $user = User::find($decrypted);
    
            $userDetail = UserDetail::where('user_id', $decrypted)->first();
            if(isset($userDetail)) { $userDetail->delete(); }
            
            $memberDetail = MemberDetail::where('user_id', $decrypted)->first();
            if(isset($memberDetail)) { $memberDetail->delete(); }
            
            $audits = Audit::where('user_id', $user->id);
            if(isset($audits) && $audits->get()->count() > 0) { $audits->delete(); }
            
            $notifications = MemberNotificationView::where('user_id', $user->id)->delete();
            
            $pollvotes = PollVote::where('user_id', $user->id)->delete();
    
            $ipns = Ipn::where('ccustemail', $user->email)->delete();
            
            $this->deleteStore($user);
    
            $user->delete();
    
            Session::flash('success', 'Successfully deleted '.$user->name);
        }else{
            Session::flash('error', 'Please enter DELETE to confirm the action.');
        }

        return redirect()->route('members.index');
    }


    public function deleteStore($user){
        $stores = Store::where('user_id', $user->id)->get();
        
        foreach ($stores as $store) {
            $directory = base_path('img/uploads/' . $store->subdomain . '/');
        
            if(!isset($store)){
                Session::flash("error", "Cannot find store to delete. Please try again.");
                return redirect()->route('listStore');
            }

            $alliates_settings = AffiliateSetting::where('store_id', $store->id)->delete();
            $social_settings = SocialSetting::where('store_id', $store->id)->delete();
            $smo_settings = SmoSetting::where('store_id', $store->id)->delete();

            //delete store busines profile 
            $businessProfile = ContactUsProfile::where('store_id', $store->id)->delete();
            // store settings
            $store_theme = StoreTheme::where('store_id', $store->id)->delete();
            //slider settings
            $sliderIds = StoreSlider::pluck('id')->where('store_id', $store->id)->toArray();
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
            foreach ($products as $product) {
                $productCategories = ProductCategory::where('product_id', $product->id)->delete();    
                $productTags = ProductTag::where('product_id', $product->id)->delete();    
                $productImages = ProductImage::where('product_id', $product->id)->delete();    
                $productVideos = ProductVideo::where('product_id', $product->id)->delete();    
                $productSeoSettings = ProductSeoSetting::where('product_id', $product->id)->delete();    
                $productTweets = ProductTweet::where('product_id', $product->id)->delete();    

                $product->delete();
            }

            // delete store categories
            $categories = Category::where('store_id', $store->id)->delete();
            // delete store tags
            $tags = Tag::where('store_id', $store->id)->delete();
            
            if(File::exists($directory)) 
                File::deleteDirectory($directory);

            $store->delete();
        }
    }


    function sendMails($user, $password){
        $site = Setup::where('key', 'site_name')->first();
        
        $this->sendToMember($user, $site, $password);
        $this->sendToAdmin($user, $site, $password);
        $this->sendToAutoResponder($user, $site);

        return true;
    }

    function sendToMember($user, $site, $password){
        $email = EmailResponder::find(1);
        $from = $email->from;
        $to = $user->email;
        $body = $email->body;

        $subject = str_replace('%SITENAME%', $site->value , $email->subject);
        $body = str_replace('%FNAME%', $user->name , $body);
        $body = str_replace('%SITENAME%', $site->value , $body);
        $body = str_replace('%EMAIL%', $user->email , $body);
        $body = str_replace('%PASS%', $password , $body);

        Mail::send([], [], function ($message) use ($from, $to, $body, $subject, $site, $user) {
            $message->from(env('MAIL_USERNAME'), $site->value);
            $message->to($to, $user->name);
            $message->subject($subject);
            $message->setBody($body, 'text/html');
        });
    }

    function sendToAdmin($user, $site, $password){
        $email = EmailResponder::find(2);
        $from = $email->from;
        $to = $email->to;
        $body = $email->body;

        $subject = str_replace('%SITENAME%', $site->value , $email->subject);
        $body = str_replace('%SITENAME%', $site->value , $body);
        $body = str_replace('%FNAME%', $user->detail->first_name , $body);
        $body = str_replace('%LNAME%', $user->detail->last_name , $body);
        $body = str_replace('%EMAIL%', $user->email , $body);
        $body = str_replace('%PASS%', $password , $body);

        Mail::send([], [], function ($message) use ($from, $to, $body, $subject, $site, $user) {
            $message->from(env('MAIL_USERNAME'), $site->value);
            $message->to($to, $user->name);
            $message->subject($subject);
            $message->setBody($body, 'text/html');
        });
    }

    function sendToAutoResponder($user, $site){
        $email = EmailResponder::find(3);
        $from = $email->from;
        $to = $email->to;
        $body = $email->body;
        
        $subject = str_replace('%SITENAME%', $site->value , $email->subject);
        $body = str_replace('%FNAME%', $user->detail->first_name , $body);
        $body = str_replace('%LNAME%', $user->detail->last_name , $body);
        $body = str_replace('%EMAIL%', $user->email , $body);

        Mail::send([], [], function ($message) use ($from, $to, $body, $subject, $site, $user) {
            $message->from(env('MAIL_USERNAME'), $site->value);
            $message->to($to, $user->name);
            $message->subject($subject);
            $message->setBody($body, 'text/html');
        });
    }

    public function ipnList(){
        $ipns = Ipn::orderBy('id', 'DESC')->get();
        return view('admin.members.ipn', compact('ipns'));
    }
}
