<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Thujohn\Twitter\Facades\Twitter;
use DirkGroenen\Pinterest\Pinterest;
use App\Store;
use App\Product;
use App\ProductCategory;
use App\SocialSetting;
use App\SocialCampaign;
use App\SocialCampaignLog;

class ShareSocialCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Post / Share products on social media sites';

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
        $stores = Store::all();

        foreach ($stores as $store) {
            $campaigns = SocialCampaign::where('store_id', $store->id)
                    ->where('enable_autopost', 1)
                    ->whereDate('schedule_date', date('Y-m-d'))
                    ->where('schedule_time', '<=', date('H:i:s'))
                    ->where('is_posted', '<=', 0)
                    ->get();

            if(count($campaigns) > 0){
                foreach ($campaigns as $campaign) {
                    $products = Product::whereIn('id', json_decode($campaign->products))->get();

                    foreach ($products as $product) {
                        $url = route('index.product.show', ['subdomain' => $store->subdomain, 'permalink' =>  $product->permalink]);
                        $fb = \App::make('SammyK\LaravelFacebookSdk\LaravelFacebookSdk');
                    
                        //post facebook timeline
                        // if($campaign->campaigns->where('type', 'facebook_timeline')->count() > 0)
                        //     $this->facebookTimeline($store, $url);

                        //post facebook group'
                        if($campaign->campaigns->where('type', 'facebook_groups')->count() > 0){
                            $groups = $this->facebookGroup($store, $url, $product->id, $fb);

                            foreach ($groups as $group) {
                                $post = $this->postingFacebookGroup($store, $url, $product->id, $fb, $group);

                                if($post){
                                    $this->insertLog($store, $url, $product->id, 1);
                                }else{
                                    $this->insertLog($store, $url, $product->id, 0);
                                }
                            }
                        }
                           
                        //post facebook pages
                        if($campaign->campaigns->where('type', 'facebook_pages')->count() > 0)
                            $this->facebookPages($store, $url, $product->id, $fb);

                        // post tweet
                        if($campaign->campaigns->where('type', 'twitter')->count() > 0)
                            $this->tweet($store, $url, $product->id);
                       
                        // post tumblr
                        if($campaign->campaigns->where('type', 'tumblr')->count() > 0)
                            $this->tumblr($store, $url, $product);

                        // pin to pinterest
                        if($campaign->campaigns->where('type', 'pinterest')->count() > 0)
                            $this->pinterest($store, $url, $product);

                        // post instagram
                        if($campaign->campaigns->where('type', 'instagram')->count() > 0)
                            $this->instagram($store, $url);
                    }

                    $campaign->update([
                        'is_posted' => 1
                    ]);
                }
            }
        }
    }

    public function facebookTimeline($store, $url){
        try{
            $socialSetting = SocialSetting::where('store_id', $store->id)->where('name', 'facebook')->first();
            if($this->validCredentials($socialSetting)){
                $settings = json_decode($socialSetting->settings);
                return true;

            }else{
                return false;
            }
        }catch(\Exception $e){
            return false;
        }
    }    

    public function facebookGroup($store, $url, $productId, $fb){
        try{
            $socialSetting = SocialSetting::where('store_id', $store->id)->where('name', 'facebook')->first();

            if($this->validCredentials($socialSetting)){
                $settings = json_decode($socialSetting->settings);
                
                $facebook = $fb->newInstance([
                    'app_id' => $settings->application_id,
                    'app_secret' => $settings->application_secret,
                ]);
    
                // Returns a `Facebook\FacebookResponse` object
                $response = $facebook->get('/me/groups?admin_only=true', $settings->access_token);

                $groups = json_decode($response->getBody())->data;
                $groupIds = [];

                for($i = 0; $i < count($groups); $i++){
                    array_push($groupIds, $groups[$i]->id);
                }

                return $groupIds;
            }else{
                return [];
            }
        }catch(\Exception $e){
            return [];
        }
    }  

    public function postingFacebookGroup($store, $url, $productId, $fb, $group){
        try{
            $socialSetting = SocialSetting::where('store_id', $store->id)->where('name', 'facebook')->first();
            $settings = json_decode($socialSetting->settings);
            
            $facebook = $fb->newInstance([
                'app_id' => $settings->application_id,
                'app_secret' => $settings->application_secret,
            ]);

            $response = $facebook->post(
                '/'. $group .'/feed', [
                    'link' => $url,
                ], $settings->access_token
            );
            
            if(isset($response)){
                return true;
            }else{
                return false;
            }
        }catch(\Exceptions $e){
            return false;
        }
    }

    public function facebookPages($store, $url, $productId, $fb){
        // try{
            $socialSetting = SocialSetting::where('store_id', $store->id)->where('name', 'facebook')->first();

            if($this->validCredentials($socialSetting)){
                $settings = json_decode($socialSetting->settings);
                
                $facebook = $fb->newInstance([
                    'app_id' => $settings->application_id,
                    'app_secret' => $settings->application_secret,
                ]);
    
                // Returns a `Facebook\FacebookResponse` object
                $response = $facebook->get('/me/accounts', $settings->access_token);
                foreach ($response->getDecodedBody()['data'] as $page) {
                    $result = $facebook->post('/'.$page['id'].'/feed',
                        ['link' => $url],
                        $page['access_token']
                    );

                    if(isset($result)){
                        $this->insertLog($store, $url, $productId, 1);
                        return true;
                    }else{
                        $this->insertLog($store, $url, $productId, 0);
                        return false;
                    }
                }
            }else{
                return false;
            }
        // }catch(\Exception $e){
        //     return false;
        // }
    }   

    public function tweet($store, $url, $productId){
        try{
            $socialSetting = SocialSetting::where('store_id', $store->id)->where('name', 'twitter')->first();

            if($this->validCredentials($socialSetting)){
                $settings = json_decode($socialSetting->settings);
                Twitter::reconfig(['consumer_key' => $settings->consumer_key, 'consumer_secret' => $settings->consumer_secret, 'token' => $settings->access_token, 'secret' => $settings->access_token_secret]);
                $tweet = ['status' => $url];
    
                $result = Twitter::postTweet($tweet);
    
                if(isset($result->text)){
                    $this->insertLog($store, $url, $productId, 1);
                    return true;
                }else{
                    $this->insertLog($store, $url, $productId, 0);
                    return false;
                }
            }else{
                return false;
            }
        }catch(\Exception $e){
            return false;
        }
    }

    public function tumblr($store, $url, $product){
        try{
            $socialSetting = SocialSetting::where('store_id', $store->id)->where('name', 'tumblr')->first();

            if($this->validCredentials($socialSetting)){
                $settings = json_decode($socialSetting->settings);

                $params = [
                    'title' => $product->name,
                    'thumbnail' => $product->image,
                    'url' => $url,
                    'type' => 'link'
                ];	
        
                $headers = [
                    'Host' => 'http://api.tumblr.com/',
                    'Content-type' => 'application/x-www-form-urlencoded',
                    'Expect' => ''
                ];
        
                $headers = $this->oauthGen("POST", "http://api.tumblr.com/v2/blog/" . $settings->blog_name . "/post", $params, $headers, $settings);
        
                $ch = curl_init();
                // curl_setopt($ch, CURLOPT_USERAGENT, "PHP Uploader Tumblr v1.0");
                curl_setopt($ch, CURLOPT_URL, "http://api.tumblr.com/v2/blog/" . $settings->blog_name . "/post");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Authorization: ' . $headers['Authorization'],
                    'Content-type: ' . $headers['Content-type'],
                    'Expect: '
                ]);
        
                $params = http_build_query($params);
        
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        
                $response = curl_exec($ch);
                $json = json_decode($response,true);  
                
                if($json && $json['meta']['status'] !== 401){
                    $this->insertLog($store, $url, $product->id, 1);
                    return true;
                }else{
                    $this->insertLog($store, $url, $product->id, 0);
                    return false;
                }
            }else{
                return false;
            }
        }catch(\Exception $e){
            return false;
        }
    }

    public function oauthGen($method, $url, $iparams, &$headers, $settings) {
    
        $iparams['oauth_consumer_key'] = $settings->consumer_key;
        $iparams['oauth_nonce'] = strval(time());
        $iparams['oauth_signature_method'] = 'HMAC-SHA1';
        $iparams['oauth_timestamp'] = strval(time());
        $iparams['oauth_token'] = $settings->oauth_token;
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

        return base64_encode(hash_hmac('sha1', $sig, $settings->consumer_secret ."&". $settings->oauth_secret, true));
    }

    public function pinterest($store, $url, $product){
        try{
            $socialSetting = SocialSetting::where('store_id', $store->id)->where('name', 'pinterest')->first();

            if($this->validCredentials($socialSetting)){
                $settings = json_decode($socialSetting->settings);

                $pinterest = new Pinterest($settings->application_id, $settings->application_secret);
                $pinterest->auth->setOAuthToken($settings->access_token);

                $boardName = str_replace(' ', '-', $settings->board_name);
                $boardPath = $settings->username .'/'. $boardName;

                $pin = $pinterest->pins->create(array(
                    "note" => $product->name,
                    "image_url" => $product->image,
                    "link" => $url,
                    "board" => $boardPath
                ));

                if(isset(json_decode($pin)->id)){
                    $this->insertLog($store, $url, $product->id, 1);
                    return true;
                }else{
                    $this->insertLog($store, $url, $product->id, 0);
                    return false;
                }
            }else{
                return [];
            }
        }catch(\Exception $e){
            return [];
        }
    }

    public function instagram($store, $url){
        try{
            $socialSetting = SocialSetting::where('store_id', $store->id)->where('name', 'instagram')->first();
            if($this->validCredentials($socialSetting)){
                $settings = json_decode($socialSetting->settings);
                return true;

            }else{
                return false;
            }
        }catch(\Exception $e){
            return false;
        }
    }   

    public function validCredentials($socialSetting){
        $settings = json_decode($socialSetting->settings);
        foreach ($settings as $setting) {
            if($setting == ""){
                return false;
                break;
            }
        }

        return true;
    }

    public function insertLog($store, $url, $productId, $status){
        SocialCampaignLog::create([
            'store_id' => $store->id, 
            'product_id' => $productId, 
            'posted_to' => 'Twitter Timeline', 
            'link' => $url, 
            'status' => $status, 
            'posted_date' => $status == 0 ? '' : date('Y-m-d')
        ]);
    }
}
