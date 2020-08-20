<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use Thujohn\Twitter\Facades\Twitter;
use GuzzleHttp\Client;
use SimpleXMLElement;
use Session;
use Redirect;
use Crypt;
use Mail;
use Auth;
use \Swift_Mailer;
use \Swift_SmtpTransport as SmtpTransport;
use App\AffiliateSetting;
use App\SmoSetting;
use App\SocialSetting;
use App\DesignOption;
use App\Store;
use App\ContactUsProfile;
use App\ContactUsMessage;
use App\ContactSmtp;
use App\Feature;
use App\AccessFeature;
use App\MarketPlaceInstruction;
use DirkGroenen\Pinterest\Pinterest;

class SettingsController extends GlobalController
{
    public function affiliate(){
        $store = $this->getCurrentStore();
        $otherStores = Store::where('user_id', Auth::user()->id)->where('id', '<>', $store->id)->get();
        $affiliateSettings = AffiliateSetting::where('store_id', $store->id)->get();
        $affiliates = $this->getAffiliateStores();
        $marketPlaces = $this->getMarketPlaces();
        $instructions = $this->getInstructions();
        
        return view('settings.affiliate', compact('affiliateSettings', 'otherStores', 'affiliates', 'marketPlaces', 'instructions'));
    }

    public function getInstructions(){
        $marketPlaces = $this->marketPlaces();
        $instructions= [];

        foreach ($marketPlaces as $key => $marketPlace) {
            $instructions[$key] = MarketPlaceInstruction::where('market_place', $key)->first();
        }

        return $instructions;
    }

    public function marketPlaces(){
        return [
            'amazon' => 'Amazon', 
            'ebay' => 'Ebay', 
            'aliexpress' => 'AliExpress', 
            'walmart' => 'Walmart', 
            'shopcom' => 'Shop.com', 
            'cjcom' => 'Cj.com', 
            'jvzoo' => 'JVZoo', 
            'clickbank' => 'ClickBank', 
            'warriorplus' => 'Warrior Plus', 
            'paydotcom' => 'PayDotCom'
        ];
    }

    public function getMarketPlaces(){
        return [
            'Australia' => 'webservices.amazon.com.au',
            'Brazil' => 'webservices.amazon.com.br',
            'Canada' => 'webservices.amazon.ca',
            'China' => 'webservices.amazon.cn',
            'France' => 'webservices.amazon.fr',
            'Germany' => 'webservices.amazon.de',
            'India' => 'webservices.amazon.in',
            'Italy' => 'webservices.amazon.it',
            'Japan' => 'webservices.amazon.co.jp',
            'Mexico' => 'webservices.amazon.com.mx',
            'Spain' => 'webservices.amazon.es',
            'Turkey' => 'webservices.amazon.com.tr',
            'United Kingdom' => 'webservices.amazon.co.uk',
            'United States' => 'webservices.amazon.com',
        ];
    }

    public function getAffiliateStores(){
        $affiliates = [];
        $features = AccessFeature::where('membership_id', Auth::user()->memberDetail->membership->id)->get();

        foreach ($features as $feature) {
            array_push($affiliates, strtolower($feature->features->name));
        }

        return $affiliates;
    }

    public function importAffiliateAllSettings(Request $request, $subdomain){
        $this->validate($request, [
            'store' => 'required'
        ]);

        $storeId = Crypt::decrypt($request->store);
        $exportStores = AffiliateSetting::where('store_id', $storeId)->get();
        $store = $this->getCurrentStore();

        foreach ($exportStores as $exportStore) {
            if(isset($exportStore->name)){
                $affiliateSettings = AffiliateSetting::where('store_id', $store->id)->where('name', $exportStore->name)->first();

                if(!isset($affiliateSettings)){
                    AffiliateSetting::create([
                        'store_id' => $store->id, 
                        'name' => $exportStore->name, 
                        'settings' => $exportStore->settings
                    ]);
                }else{
                    $affiliateSettings->update([
                        'settings' => $exportStore->settings
                    ]);
                }
            }
        }

        Session::flash('success', 'Settings imported successfully');
        return redirect()->back();
    }

    public function importAffiliateSettings(Request $request, $subdomain){
        $this->validate($request, [
            'store' => 'required'
        ]);

        $storeId = Crypt::decrypt($request->store);
        $exportStore = AffiliateSetting::where('store_id', $storeId)->where('name', $request->type)->first();
        $store = $this->getCurrentStore();

        if(isset($exportStore)){
            $affiliateSettings = AffiliateSetting::where('store_id', $store->id)->where('name', $exportStore->name)->first();
        
            if(!isset($affiliateSettings)){
                AffiliateSetting::create([
                    'store_id' => $store->id, 
                    'name' => $exportStore->name, 
                    'settings' => $exportStore->settings
                ]);
            }else{
                $affiliateSettings->update([
                    'settings' => $exportStore->settings
                ]);
            }

            Session::flash('success', 'Settings imported successfully');
        }else{
            Session::flash('error', 'Import settings failed. No '. ucfirst($request->type) .' affiliate settings found');
        }

        return redirect()->back();
    }

    public function amazon(Request $request){
        $store = $this->getCurrentStore();

        $this->validate($request, [
            'associate_tag' => 'required',
            'access_key' => 'required',
            // 'secret_key' => 'required',
        ],[
            'associate_tag.required' => 'Associate ID field is required.'
        ]);

        if(isset($request->useOwnKeys)){
            $affiliateSettings = AffiliateSetting::where('store_id', $store->id)->where('name', 'amazon')->first();
            
            $amazon = [
                'market_place' => $request->market_place,
                'associate_tag' => $request->associate_tag,
                'access_key_id' => $request->access_key,
                'secret_access_key' => $request->secret_key,
                'use_own_keys' => 'false',
            ];
    
            $affiliateSettings->update([
                'settings' => json_encode($amazon)
            ]);
        
            Session::flash('success', 'Amazon Settings successfully updated');
        }else{
            $isValid = $this->validateAmazonKeys($request);
            if($isValid == true){
                $affiliateSettings = AffiliateSetting::where('store_id', $store->id)->where('name', 'amazon')->first();
            
                $amazon = [
                    'market_place' => $request->market_place,
                    'associate_tag' => $request->associate_tag,
                    'access_key_id' => $request->access_key,
                    'secret_access_key' => $request->secret_key,
                    'use_own_keys' => 'true',
                ];
        
                $affiliateSettings->update([
                    'settings' => json_encode($amazon)
                ]);
        
                Session::flash('success', 'Amazon Settings successfully updated');
            }else{
                Session::flash('error', 'Amazon AWS key are not correct');
            }
        }

        return redirect()->back();
    }

    public function validateAmazonKeys($request){
        $secret_key = $request->secret_key;
        $common_params = [
            'Service' => 'AWSECommerceService',
            'Operation' => 'ItemSearch',
            'ResponseGroup' => 'Images,ItemAttributes,ItemIds,Reviews,OfferSummary',
            'AssociateTag' => $request->associate_tag,
            'AWSAccessKeyId' => $request->access_key,
        ];

        $query = [
            'Keywords' => '*',
            'SearchIndex' => 'All'
        ];

        $locale = $request->market_place;
        $timestamp = date('c');
        $query['Timestamp'] = $timestamp;
        $query = array_merge($common_params, $query);
        $query['Signature'] = $this->generateSignature($query, $secret_key, $locale);
        $client = new Client();

        try {
            $response = $client->request(
                'GET', 'http://' .$locale. '/onca/xml', 
                ['query' => $query]
            );
            
            $contents = new SimpleXMLElement($response->getBody()->getContents());
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function generateSignature($query, $secret_key, $locale)
    {
      ksort($query);
  
      $sign = http_build_query($query);
  
      $request_method = 'GET';
      $base_url = $locale;
      $endpoint = '/onca/xml';
  
      $string_to_sign = "{$request_method}\n{$base_url}\n{$endpoint}\n{$sign}";
      $signature = base64_encode(
          hash_hmac("sha256", $string_to_sign, $secret_key, true)
      );

      return $signature;
    }

    public function ebay(Request $request){
        $store = $this->getCurrentStore();
        
        $this->validate($request, [
            'application_id' => 'required'
        ]);
        
        $isValid = $this->validateEbay($request);
        
        if($isValid == true){
            $affiliateSettings = AffiliateSetting::where('store_id', $store->id)->where('name', 'ebay')->first();
        
            $ebay = [
                'application_id' => $request->application_id,
                'network_id' => isset($request->networkId) ? $request->networkId : '',
                'tracking_id' => $request->tracking_id,
                'custom_id' => isset($request->custom_id) ? $request->custom_id : ''
            ];
    
            $affiliateSettings->update([
                'settings' => json_encode($ebay)
            ]);
    
            Session::flash('success', 'Ebay Settings successfully updated');
        }else{
            Session::flash('error', 'Ebay Api key incorrect');
        }

        return redirect()->back();
    }

    public function validateEbay($request){
        $query = [
            'OPERATION-NAME' => 'findItemsAdvanced',
            'SERVICE-VERSION' => '1.0.0',
            'GLOBAL-ID' => 'EBAY-US',
            'SECURITY-APPNAME' => $request->application_id,
            'RESPONSE-DATA-FORMAT' => 'JSON',
            'REST-PAYLOAD' => '',
            'affiliate.networkId' => isset($request->networkId) ? $request->networkId : '',
            'affiliate.trackingId' => isset($request->trackingId) ? $request->trackingId : '',
            'affiliate.customId' => isset($request->custom_id) ? $request->custom_id : '',
            'keywords' => '',
            'categoryId' => '1',
            'paginationInput.entriesPerPage' => '2',
            'paginationInput.pageNumber' => '1',
        ];

        $query = http_build_query($query);
        $client = new Client();
        
        try {
            $response = $client->request(
                'GET', 'http://svcs.ebay.com/services/search/FindingService/v1', 
                ['query' => $query]
            );

            $contents = json_decode($response->getBody()->getContents());

            if($contents->findItemsAdvancedResponse[0]->ack[0] == 'Success')
                return true;
            else
                return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function aliexpress(Request $request){
        $store = $this->getCurrentStore();
        
        $this->validate($request, [
            'api_key' => 'required',
            'deep_link_hash' => 'required',
        ]);

        $isValid = $this->validateAliExpressKeys($request);

        if($isValid){
            $affiliateSettings = AffiliateSetting::where('store_id', $store->id)->where('name', 'aliexpress')->first();
        
            $aliexpress = [
                'key' => $request->api_key,
                'deep_link_hash' => $request->deep_link_hash,
            ];
    
            $affiliateSettings->update([
                'settings' => json_encode($aliexpress)
            ]);
            
            Session::flash('success', 'AliExpress Settings successfully updated');
        }else{
            Session::flash('error', 'AliExpress Keys are incorrect!');
        }
      
        return redirect()->back();
    }

    public function validateAliExpressKeys($request){
        $query = [
            'user_api_key' => $request->api_key,
            'user_hash' => $request->deep_link_hash,
            "api_version" =>  "2"
        ];

        $query['requests'] = [
            'response' => [
                "action" =>  "list_categories",
            ]
        ];

        $client = new Client();

        try {
            $response = $client->request(
                'POST', 'http://api.epn.bz/json',
                ['json' => $query]
            );

            $result = json_decode($response->getBody()->getContents());

            if(isset($result->error)){
                return false;
            }else{
                return true;
            }
            return $response->getBody()->getContents();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function walmart(Request $request){
        $store = $this->getCurrentStore();

        $this->validate($request, [
            'key' => 'required',
        ]);

        $isValid = $this->validateWalmartKey($request);

        if($isValid == true){
            $affiliateSettings = AffiliateSetting::where('store_id', $store->id)->where('name', 'walmart')->first();
            
            $walmart = [
                'key' => $request->key,
            ];

            if(isset($affiliateSettings)){
                $affiliateSettings->update([
                    'settings' => json_encode($walmart)
                ]);
            }else{
                AffiliateSetting::create([
                    'store_id' => $store->id, 
                    'name' => 'walmart', 
                    'settings' => json_encode($walmart)
                ]);
            }
          
            Session::flash('success', 'Walmart Settings successfully updated');
        }else{
            Session::flash('error', 'Walmart API key is not correct');
        }

        return redirect()->back();
    }

    public function validateWalmartKey($request){
        $client = new Client();

        $query = [
            'apiKey' => $request->key,
            'query' => 'any',
            'format' => 'json'
        ];

        try {
            $response = $client->request(
                'GET', 'http://api.walmartlabs.com/v1/search',
                ['query' => $query]
            );
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function shopcom(Request $request){
        $store = $this->getCurrentStore();

        $this->validate($request, [
            'publisher_id' => 'required',
            'api_key' => 'required',
        ]);

        $isValid = $this->validateShopCom($request);

        if($isValid == true){
            $affiliateSettings = AffiliateSetting::where('store_id', $store->id)->where('name', 'shopcom')->first();
            
            $shopcom = [
                'publisher_id' => $request->publisher_id,
                'api_key' => $request->api_key,
            ];

            if(isset($affiliateSettings)){
                $affiliateSettings->update([
                    'settings' => json_encode($shopcom)
                ]);
            }else{
                AffiliateSetting::create([
                    'store_id' => $store->id, 
                    'name' => 'shopcom', 
                    'settings' => json_encode($shopcom)
                ]);
            }
          
            Session::flash('success', 'Shop.com Settings successfully updated');
        }else{
            Session::flash('error', 'Shop.com API key or Publisher ID is not correct');
        }

        return redirect()->back();
    }

    public function validateShopCom($request){
        $client = new Client();

        $query = [
            'apikey' => $request->api_key,
            'publisherID' => $request->publisher_id,
            'locale' => 'en_US',
            'term' => ''
        ];

        try {
            $response = $client->request(
                'GET', 'https://api.shop.com/AffiliatePublisherNetwork/v1/products',
                ['query' => $query]
            );
            
            if(json_decode($response->getBody()->getContents())->numberOfProducts > 0)
                return true;
            else
                return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function cjcom(Request $request){
        $store = $this->getCurrentStore();

        $this->validate($request, [
            'website_id' => 'required',
            'api_key' => 'required',
        ]);

        $isValid = $this->validateCjCom($request);

        if($isValid == true){
            // $affiliateSettings = AffiliateSetting::where('store_id', $store->id)->where('name', 'cjcom')->first();
            
            // $cjcom = [
            //     'website_id' => $request->website_id,
            //     'api_key' => $request->api_key,
            // ];

            // if(isset($affiliateSettings)){
            //     $affiliateSettings->update([
            //         'settings' => json_encode($cjcom)
            //     ]);
            // }else{
            //     AffiliateSetting::create([
            //         'store_id' => $store->id, 
            //         'name' => 'cjcom', 
            //         'settings' => json_encode($cjcom)
            //     ]);
            // }
          
            Session::flash('success', 'Cj.com Settings successfully updated');
        }else{
            Session::flash('error', 'Cj.com Website ID or API key is not correct');
        }

        return redirect()->back();
    }

    public function validateCjCom($request){
        $client = new Client();
        
        $query = [
            'website-id' => $request->website_id,
            'keywords' => '',
            'records-per-page' => '10'
        ];

        try {
            $response = $client->request(
                'GET', 'https://product-search.api.cj.com/v2/product-search', 
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $request->api_key
                    ],
                    'query' => $query
                ]
            );
            
            $contents = new SimpleXMLElement($response->getBody()->getContents());

            if(isset($contents->products->product)){
                return true;
            }else{
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function jvzoo(Request $request){
        $store = $this->getCurrentStore();

        $this->validate($request, [
            'affiliate_id' => 'required',
        ]);

        $affiliateSettings = AffiliateSetting::where('store_id', $store->id)->where('name', 'jvzoo')->first();
        
        $jvzoo = [
            'affiliate_id' => $request->affiliate_id,
        ];

        if(isset($affiliateSettings)){
            $affiliateSettings->update([
                'settings' => json_encode($jvzoo)
            ]);
        }else{
            AffiliateSetting::create([
                'store_id' => $store->id, 
                'name' => 'jvzoo', 
                'settings' => json_encode($jvzoo)
            ]);
        }
        
        Session::flash('success', 'JVZoo Settings successfully updated');
        

        return redirect()->back();
    }

    public function clickbank(Request $request){
        $store = $this->getCurrentStore();

        $this->validate($request, [
            'account_name' => 'required',
        ]);

        $affiliateSettings = AffiliateSetting::where('store_id', $store->id)->where('name', 'clickbank')->first();
        
        $clickbank = [
            'account_name' => $request->account_name,
        ];

        if(isset($affiliateSettings)){
            $affiliateSettings->update([
                'settings' => json_encode($clickbank)
            ]);
        }else{
            AffiliateSetting::create([
                'store_id' => $store->id, 
                'name' => 'clickbank', 
                'settings' => json_encode($clickbank)
            ]);
        }
        
        Session::flash('success', 'ClickBank Settings successfully updated');

        return redirect()->back();
    }

    public function social(){
        $store = $this->getCurrentStore();
        $socialSettings = SocialSetting::where('store_id', $store->id)->get();

        return view('settings.social', compact('socialSettings'));
    }

    public function facebookPages(\SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb){
        $store = $this->getCurrentStore();
        $socialSetting = SocialSetting::where('store_id', $store->id)->where('name', 'facebook')->first();
        $settings = json_decode($socialSetting->settings);

        try {
            $facebook = $fb->newInstance([
                'app_id' => $settings->application_id,
                'app_secret' => $settings->application_secret,
            ]);

            // Returns a `Facebook\FacebookResponse` object
            $response = $facebook->get('/me/accounts', $settings->access_token);

            foreach ($response->getDecodedBody()['data'] as $page) {
                foreach ($store->products->take(2) as $product) {
                    $url = route('index.product.show', ['subdomain' => 'scubba', 'permalink' =>  $product->permalink]);
                    
                    $response = $fb->post(
                        '/'.$page['id'].'/feed',
                        [
                        'link' => $url
                        ],
                        $page['access_token']
                    );
                }
            }
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            dd($e->getMessage());
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            dd($e->getMessage());
        }
    }

    // public function facebookTimeline(\SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb){
    //     $store = $this->getCurrentStore();
    //     $socialSetting = SocialSetting::where('store_id', $store->id)->where('name', 'facebook')->first();
    //     $settings = json_decode($socialSetting->settings);

    //     try {
    //         $facebook = $fb->newInstance([
    //             'app_id' => $settings->application_id,
    //             'app_secret' => $settings->application_secret,
    //         ]);

    //         foreach ($store->products->whereIn('id', ['6', '10']) as $product) {
    //             $url = route('index.product.show', ['subdomain' => 'scubba', 'permalink' =>  $product->permalink]);
                
    //             $response = $fb->post(
    //                 '/me/feed',
    //                 ['link' => $url],
    //                 $settings->access_token
    //             );
    //         }
    //     } catch(Facebook\Exceptions\FacebookResponseException $e) {
    //         dd($e->getMessage());
    //     } catch(Facebook\Exceptions\FacebookSDKException $e) {
    //         dd($e->getMessage());
    //     }
    // }

    public function facebook(Request $request, \SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb){
        $store = $this->getCurrentStore();
        
        $this->validate($request, [
            'application_id' => 'required',
            'application_secret' => 'required',
        ]);
    
        try {
            $facebook = $fb->newInstance([
                'app_id' => $request->application_id,
                'app_secret' => $request->application_secret,
            ]);

            Session::put('fb_app_id', $request->application_id);
            Session::put('fb_app_secret', $request->application_secret);
            $url = $facebook->getLoginUrl(['email', 'manage_pages', 'publish_pages', 'publish_to_groups']);
            return redirect()->to($url);
        }catch(\Exceptions $e){
            Session::flash('error', $e->get('Message'));
            return redirect()->back();
        }
        // } catch (Facebook\Exceptions\FacebookResponseException $e) {
        //     $graphError = $e->getPrevious();
        //     echo 'Graph API Error: ' . $e->getMessage();
        //     echo ', Graph error code: ' . $graphError->getCode();
        //     exit;
        // } catch (Facebook\Exceptions\FacebookSDKException $e) {
        //     echo 'SDK Error: ' . $e->getMessage();
        //     exit;
        // }


        // $socialSetting = SocialSetting::where('store_id', $store->id)->where('name', 'facebook')->first();
        
        // $facebook = [
        //     'application_id' => $request->application_id,
        //     'application_secret' => $request->application_secret,
        // ];

        // $socialSetting->update([
        //     'settings' => json_encode($facebook)
        // ]);
        
        // Session::flash('success', 'Facebook settings successfully updated');
        // return redirect()->back();
    }

    public function facebookCallback(\SammyK\LaravelFacebookSdk\LaravelFacebookSdk $fb){
        $store = $this->getCurrentStore();

        try {
            $facebook = $fb->newInstance([
                'app_id' => Session::get('fb_app_id'),
                'app_secret' => Session::get('fb_app_secret'),
            ]);

            $token = $facebook->getAccessTokenFromRedirect();
            if (! $token) {
                    // Get the redirect helper
                $helper = $facebook->getRedirectLoginHelper();

                if (! $helper->getError()) {
                    Session::flash('error', 'Unauthorized action.');
                    return redirect()->route('social', $store->subdomain);
                }
                
                Session::flash('error', 'Unauthorized action.');
                return redirect()->route('social', $store->subdomain);
            }
            

            if (! $token->isLongLived()) {
                $oauth_client = $facebook->getOAuth2Client();

                try {
                    $token = $oauth_client->getLongLivedAccessToken($token);
                } catch (Facebook\Exceptions\FacebookSDKException $e) {
                    dd($e->getMessage());
                }
            }

            $facebook->setDefaultAccessToken($token);
            $socialSetting = SocialSetting::where('store_id', $store->id)->where('name', 'facebook')->first();
        

            try{
                $details = $facebook->get(
                    '/me/?fields=picture{url},name',
                    $token->getValue()
                );
            }catch(\Exception $e){
                Session::flash('error', $e->getMessage());
                return redirect()->route('social', $store->subdomain);
            }

            $data = [
                'application_id' => Session::get('fb_app_id'),
                'application_secret' => Session::get('fb_app_secret'),
                'access_token' => $token->getValue(),
                'id' => ($details->getDecodedBody() !== null) ? $details->getDecodedBody()['id'] : '',
                'name' => ($details->getDecodedBody() !== null) ? $details->getDecodedBody()['name'] : '',
                'avatar' => isset($details->getDecodedBody()['picture']) ? $details->getDecodedBody()['picture']['data']['url'] : '',
            ];

            $socialSetting->update([
                'settings' => json_encode($data)
            ]);

            Session::forget('fb_app_id');
            Session::forget('fb_app_secret');
            Session::flash('success', 'Facebook settings successfully updated');

            return redirect()->route('social', $store->subdomain);
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('social', $store->subdomain);
        }
    }

    public function twitter(Request $request){
        $store = $this->getCurrentStore();
        
        $this->validate($request, [
            'consumer_key' => 'required',
            'consumer_secret' => 'required',
            'access_token' => 'required',
            'access_token_secret' => 'required',
        ]);

        $isValid = $this->validateTwitter($request);

        if($isValid){
            $socialSetting = SocialSetting::where('store_id', $store->id)->where('name', 'twitter')->first();
        
            $twitter = [
                'consumer_key' => $request->consumer_key,
                'consumer_secret' => $request->consumer_secret,
                'access_token' => $request->access_token,
                'access_token_secret' => $request->access_token_secret,
                'id' => $isValid->id_str,
                'name'=> $isValid->name,
                'screen_name' => $isValid->screen_name,
                'avatar' => isset($isValid->profile_image_url_https) ? $isValid->profile_image_url_https : $isValid->profile_image_url
            ];
    
            $socialSetting->update([
                'settings' => json_encode($twitter)
            ]);
            
            Session::flash('success', 'Twitter settings successfully updated');
        }else{
            Session::flash('error', 'Twitter Keys are invalid');
        }
        
        return redirect()->back();
    }

    public function validateTwitter($request){
        try{
            Twitter::reconfig([
                'consumer_key' => $request->consumer_key, 
                'consumer_secret' => $request->consumer_secret, 
                'token' => $request->access_token, 
                'secret' => $request->access_token_secret
            ]);

            $credentials = Twitter::getCredentials();

            if($credentials)
                return $credentials;
            else    
                return false;
        }catch(\Exception  $e){
            return false;
        }
    }

    public function tumblr(Request $request){
        $store = $this->getCurrentStore();
        
        $this->validate($request, [
            'consumer_key' => 'required',
            'consumer_secret' => 'required',
            'oauth_token' => 'required',
            'oauth_secret' => 'required',
            'blog_name' => 'required',
        ]);

        $isValid = $this->validateTumblr($request);

        if($isValid && $isValid['meta']['status'] !== 401){
            $socialSetting = SocialSetting::where('store_id', $store->id)->where('name', 'tumblr')->first();
        
            $tumblr = [
                'consumer_key' => $request->consumer_key,
                'consumer_secret' => $request->consumer_secret,
                'oauth_token' => $request->oauth_token,
                'oauth_secret' => $request->oauth_secret,
                'blog_name' => $request->blog_name,
                'name'=> $isValid['response']['user']['name'],
            ];
    
            $socialSetting->update([
                'settings' => json_encode($tumblr)
            ]);
            
            Session::flash('success', 'Tumblr settings successfully updated');
        }else{
            Session::flash('error', 'Tumblr Keys are invalid');
        }
        
        return redirect()->back();
    }

    public function validateTumblr($request)
    {
        try{
            $settings = [
                'consumer_key' => $request->consumer_key,
                'consumer_secret' => $request->consumer_secret,
                'oauth_token' => $request->oauth_token,
                'oauth_secret' => $request->oauth_secret,
                'blog_name' => $request->blog_name,
            ];
           
            $headers = [
                'Host' => 'http://api.tumblr.com/',
                'Content-type' => 'application/x-www-form-urlencoded',
                'Expect' => ''
            ];
    
            $headers = $this->oauthGen("GET", "http://api.tumblr.com/v2/user/info/", [], $headers, $settings);
    
            $ch = curl_init();
            // curl_setopt($ch, CURLOPT_USERAGENT, "PHP Uploader Tumblr v1.0");
            curl_setopt($ch, CURLOPT_URL, "http://api.tumblr.com/v2/user/info/");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
    
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: ' . $headers['Authorization'],
                'Content-type: ' . $headers['Content-type'],
                'Expect: '
            ]);
    
            $response = curl_exec($ch);
            $json = json_decode($response, true);  
    
            return $json;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function oauthGen($method, $url, $iparams, &$headers, $settings) {
    
        $iparams['oauth_consumer_key'] = $settings['consumer_key'];
        $iparams['oauth_nonce'] = strval(time());
        $iparams['oauth_signature_method'] = 'HMAC-SHA1';
        $iparams['oauth_timestamp'] = strval(time());
        $iparams['oauth_token'] = $settings['oauth_token'];
        $iparams['oauth_version'] = '1.0';
        $iparams['oauth_signature'] = $this->oauthSig($method, $url, $iparams, $settings);
        $iparams['oauth_signature'];  
        
        $oauth_header = [];

        foreach($iparams as $key => $value) {
            if (strpos($key, "oauth") !== false) { 
               $oauth_header []= $key ."=".$value;
            }
        }
        
        $oauth_header = "OAuth ". implode(",", $oauth_header);
        $headers["Authorization"] = $oauth_header;
        
        return $headers;
    }
    
    public function oauthSig($method, $uri, $params, $settings) {
        $parts []= $method;
        $parts []= rawurlencode($uri);
       
        $iparams = array();
        ksort($params);
        foreach($params as $key => $data) {
                if(is_array($data)) {
                    $count = 0;
                    foreach($data as $val) {
                        $n = $key . "[". $count . "]";
                        $iparams []= $n . "=" . rawurlencode($val);
                        $count++;
                    }
                } else {
                    $iparams[]= rawurlencode($key) . "=" .rawurlencode($data);
                }
        }
        $parts []= rawurlencode(implode("&", $iparams));
        $sig = implode("&", $parts);

        return base64_encode(hash_hmac('sha1', $sig, $settings['consumer_secret'] ."&". $settings['oauth_secret'], true));
    }

    public function pinterest(Request $request){
        $store = $this->getCurrentStore();
        
        $this->validate($request, [
            'application_id' => 'required',
            'application_secret' => 'required',
            'board_name' => 'required',
        ]);
    
        try {
            $pinterest = new Pinterest($request->application_id, $request->application_secret);

            Session::put('pinterest_app_id', $request->application_id);
            Session::put('pinterest_app_secret', $request->application_secret);
            Session::put('board_name', $request->board_name);

            $url = $pinterest->auth->getLoginUrl(route('pinterest.callback', $store->subdomain), ['read_public', 'write_public']);
            return redirect()->to($url);
        }catch(\Exception $e){
            Session::flash('error', $e->getMessage());
            return redirect()->back();
        }
    }

    public function pinterestCallback() {
        $store = $this->getCurrentStore();

        try {
            $applicationId = Session::get('pinterest_app_id');
            $applicationSecret = Session::get('pinterest_app_secret');
            $boardName = Session::get('board_name');

            if(!isset($_GET["code"])){
                Session::flash('error', 'Unauthorized action.');
                return redirect()->route('social', $store->subdomain);
            }

            $pinterest = new Pinterest($applicationId, $applicationSecret);
            $token = $pinterest->auth->getOAuthToken($_GET["code"]);
            $pinterest->auth->setOAuthToken($token->access_token);

            //username
            try{
                $username = json_decode($pinterest->users->me([
                    'fields' => 'username, first_name, last_name, image[small, large]'
                ]));

                $newBoardName = str_replace(' ', '-', $boardName);
                $boardPath = $username->username .'/'. $newBoardName;  

                $pinterest->boards->get($boardPath);
            } catch (\Exception $e){
                Session::flash('error', $e->getMessage());
                return redirect()->route('social', $store->subdomain);
            }

            $socialSetting = SocialSetting::where('store_id', $store->id)->where('name', 'pinterest')->first();

            $data = [
                'application_id' => $applicationId,
                'application_secret' => $applicationSecret,
                'access_token' => $token->access_token,
                'id' => $username->id,
                'board_name' => $boardName,
                'username' => $username->username,
                'img' => $username->image->large->url
            ];
    
            $socialSetting->update([
                'settings' => json_encode($data)
            ]);

            Session::forget('pinterest_app_id');
            Session::forget('pinterest_app_secret');
            Session::forget('board_name');
            Session::flash('success', 'Pinterest settings successfully updated');

            return redirect()->route('social', $store->subdomain);
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            return redirect()->route('social', $store->subdomain);
        }
    }

    public function tweet(){
        $store = $this->getCurrentStore();
        $socialSetting = SocialSetting::where('store_id', 1)->where('name', 'twitter')->first();
        $settings = json_decode($socialSetting->settings);
        Twitter::reconfig(['consumer_key' => $settings->consumer_key, 'consumer_secret' => $settings->consumer_secret, 'token' => $settings->access_token, 'secret' => $settings->access_token_secret]);
        
        $tweet = [
            'status' => "Put the gasd characters that you are going to publish to twitter here 2agsd",
        ];

        $tweet_result = Twitter::postTweet($tweet);

        dd($tweet_result);
    }

    public function instagram(Request $request){
        $store = $this->getCurrentStore();
        
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
            'client_id' => 'required',
            'client_secret' => 'required',
        ]);

        $socialSetting = SocialSetting::where('store_id', $store->id)->where('name', 'instagram')->first();
        
        $instagram = [
            'username' => $request->username,
            'password' => $request->password,
            'client_id' => $request->client_id,
            'client_secret' => $request->client_secret,
        ];

        $socialSetting->update([
            'settings' => json_encode($instagram)
        ]);
        
        Session::flash('success', 'Instagram settings successfully updated');
        return redirect()->back();
    }

    public function smo(Request $request){
        $store = $this->getCurrentStore();
        $smoSettings = SmoSetting::where('store_id', $store->id)->get();
        $design_options = DesignOption::all();

        if($request->isMethod('POST')){
            $errors = [];

            foreach ($smoSettings as $smoSetting) {
                $smoItem = SmoSetting::find($smoSetting->id);
                $page_url = $request->{ $smoSetting->name.'_page_url' };
                $display_option = isset($request->{ $smoSetting->name.'_display_option' }) ? 1 : 0;
                
                if($display_option == 1 && $page_url == "")
                    array_push($errors, 'Please enter ' .$smoItem->name. ' page url if you want to show the icon for sharing');
                else{
                    $smoItem->update([
                        'page_url' => $page_url,
                        'display_options' => $display_option
                    ]);
                }
                
            }

            if(count($errors) > 0)
                return redirect()->back()->withErrors($errors);

            Session::flash('success', 'Successfully updated account details');
            return redirect()->route('smo', $store->subdomain);
        }

        return view('settings.smo', compact('smoSettings', 'design_options'));
    }

    public function designOption(Request $request){
        $store = $this->getCurrentStore();
        $smoSettings = SmoSetting::where('store_id', $store->id);

        $smoSettings->update([
            'design_options' => $request->design_option
        ]);

        Session::flash('success', 'Desgin option settings successfully updated');
        return redirect()->back();
    }
    
    public function businessProfile(Request $request){
        $businessProfile = ContactUsProfile::where('store_id', $this->getCurrentStore()->id)->first();

        if($request->isMethod('POST')){
            $businessProfile->update([
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
            ]);

            Session::flash('success', 'Business profile succesfully updated');
            return redirect()->back();
        }

        return view('settings.business-profile', compact('businessProfile'));
    }

    public function contactMessages(){
        $contactMessages = ContactUsMessage::where('store_id', $this->getCurrentStore()->id)->get();
        $checkSmtp = $this->checkSMTP();
        
        return view('settings.contact-us', compact('contactMessages', 'checkSmtp'));
    }

    public function contactSmtp(Request $request){
        // Setup a new SmtpTransport instance for Gmail 
        $store = $this->getCurrentStore();
        $smtp = $store->smtp;

        if($request->isMethod('POST')){
            $this->validate($request, [
                'mail_host' => 'required',
                'mail_port' => 'required',
                'mail_username' => 'required',
                'mail_password' => 'required',
            ]);

            $response = $this->validateSMTP($request);

            if($response->getData()->success){
                if(isset($smtp)){
                    $smtp->update([
                        'store_id' => $store->id,
                        'host' => $request->mail_host,
                        'port' => '465',
                        'username' => $request->mail_username,
                        'password' => $request->mail_password,
                        'encryption' => 'ssl',
                    ]);
                }else{
                    $mail = ContactSmtp::create([
                        'store_id' => $store->id,
                        'host' => $request->mail_host,
                        'port' => '465',
                        'username' => $request->mail_username,
                        'password' => $request->mail_password,
                        'encryption' => 'ssl',
                    ]);
                }

                Session::flash('success', 'SMTP Setting successfully saved');
            }else{
                Session::flash('error', $response->getData()->message)->withInput($request->all());
            }

            return redirect()->back();
        }

        return view('settings.contact-us-settings', compact('smtp'));
    }

    public function validateSMTP($request){
        $store = $this->getCurrentStore();
        $success = false;

        try{
            $transport = new \Swift_SmtpTransport($request->mail_host, '465', 'ssl');
            $transport->setUsername($request->mail_username);
            $transport->setPassword($request->mail_password);

            // Assign a new SmtpTransport to SwiftMailer
            $mail = new \Swift_Mailer($transport);

            // Assign it to the Laravel Mailer
            Mail::setSwiftMailer($mail);
            // Send your message
            Mail::send([], [], function ($message) use ($request, $store) {
                $message->from(trim($request->mail_username), $store->name);
                $message->to($request->mail_username);
                $message->subject('Test');
                $message->setBody('<h2>This is a test email to validate your SMTP settings. Thank you</h2>', 'text/html');
            });

            $success = true;
            $msg = 'Valid SMTP Settings';
        }catch(\Exception $e){
            $msg = $e->getMessage();
        }

        return response()->json(['success' => $success, 'message' => $msg]);
    }

    public function replyContactMessage(Request $request){
        $decrypted = Crypt::decrypt($request->messageId);
        $message = ContactUsMessage::find($decrypted);
        $store = $this->getCurrentStore();
        $smtp = $store->smtp;

        try{
            $transport = new \Swift_SmtpTransport($smtp->host, '465', 'ssl');
            $transport->setUsername($smtp->username);
            $transport->setPassword($smtp->password);

            // Assign a new SmtpTransport to SwiftMailer
            $mail = new \Swift_Mailer($transport);

            // Assign it to the Laravel Mailer
            Mail::setSwiftMailer($mail);
            // Send your message
            Mail::send([], [], function ($msg) use ($smtp, $store, $message, $request) {
                $msg->from($smtp->username, $store->name);
                $msg->to($message->email, $message->name);
                $msg->subject($message->subject);
                $msg->setBody($request->reply, 'text/html');
            });

            Session::flash('success', 'Reply successfully sent');
        }catch(\Exception $e){
            Session::flash('success', 'Oops! Something went wrong, please try again');
        }
        
        return redirect()->back();
    }

    public function deleteContactMessage(Request $request){
        $decrypted = Crypt::decrypt($request->message_id);
        $message = ContactUsMessage::find($decrypted)->delete();

        Session::flash('success', 'Message deleted successfully');
        return redirect()->back();
    }

    public function checkSMTP(){
        $store = $this->getCurrentStore();

        if(isset($store->smtp)){
            foreach ($store->smtp as $value) {
                if($value !== ''){
                    break;
                    return false;
                }
            }

            return true;
        }else{
            return false;
        }
    }

    public function getCurrentStore(){
        $store = Store::where('subdomain', Session::get('subdomain'))->first();
        
        return $store;
    }
}
