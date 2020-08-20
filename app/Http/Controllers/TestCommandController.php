<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use Session;
use SimpleXMLElement;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
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
use App\SocialSetting;
use App\Autoresponder;
use App\User;
use App\Subscriber;

class TestCommandController extends Controller
{
    public function aweberRequestToken($data){
        $oauth = '';

        $stack = HandlerStack::create();

        $middleware = new Oauth1([
            'consumer_key'    => $data['consumer_key'],
            'consumer_secret' => $data['consumer_secret'],
            'token'           => '',
            'token_secret'    => '',
        ]);

        $stack->push($middleware);

        $client = new Client([
            'base_uri' => 'https://auth.aweber.com/1.0/oauth/',
            'handler' => $stack
        ]);

        // Set the "auth" request option to "oauth" to sign using oauth

        try{
            $response = $client->get('request_token?oauth_callback=' . route('aweber.callback', 'scubba') , ['auth' => 'oauth']);

            $oauth = $response->getBody()->getContents();
            // get the oauth_token
        }catch(\Exception $e){
            // dd($e);
        }

        return $oauth;
    }

    public function splitOauthString($string){
        $tokens = [];

        $string = explode('&', $string);

        foreach($string as $value){
            $value = explode('=', $value);

            $tokens[$value[0]] = $value[1];
        }

        return (object) $tokens;
    }

    public function getAccounts($data){
        $stack = HandlerStack::create();

        $middleware = new Oauth1([
            'consumer_key'    => $data['consumer_key'],
            'consumer_secret' => $data['consumer_secret'],
            'token'           => 'AgGEQgj1wvj5422x8p2SQzsG',
            'token_secret'    => 'Vvg6SV6CDvDOtG3ZHEmcVVkHVvrSkhJP9UavpTCO',
        ]);

        $stack->push($middleware);

        $client = new Client([
            'handler' => $stack
        ]);

        // Set the "auth" request option to "oauth" to sign using oauth

        try{
            $response = $client->get('https://api.aweber.com/1.0/accounts?ws.start=0&ws.size=10', ['auth' => 'oauth']);
            return json_decode($response->getBody()->getContents())->entries;
        }catch(\Exception $e){
            return [];
        }
    }

    public function getLists($data, $listId){
        $stack = HandlerStack::create();

        $middleware = new Oauth1([
            'consumer_key'    => $data['consumer_key'],
            'consumer_secret' => $data['consumer_secret'],
            'token'           => 'AgGEQgj1wvj5422x8p2SQzsG',
            'token_secret'    => 'Vvg6SV6CDvDOtG3ZHEmcVVkHVvrSkhJP9UavpTCO',
        ]);

        $stack->push($middleware);

        $client = new Client([
            'handler' => $stack
        ]);

        // Set the "auth" request option to "oauth" to sign using oauth

        try{
            $response = $client->get('https://api.aweber.com/1.0/accounts/' . $listId . '/lists?ws.start=0&ws.size=10', ['auth' => 'oauth']);
            return json_decode($response->getBody()->getContents())->entries;
        }catch(\Exception $e){
            return [];
        }
    }

    public function handle()
    {
        $users = User::pluck('email')->toArray();
        $subscribers = Subscriber::pluck('email')->toArray();
        $emails = array_merge($users, $subscribers);
        dd(implode(',', $emails));
        // $video = Youtube::getVideoInfo('rie-hPVJ7Sw');
        // dd($video);

        // $autoresponders = [
        //     'mailchimp' => [
        //         'api_key' => '',
        //         'list_id' => '',
        //     ],
        //     'aweber' => [
        //         'consumer_key' => '',
        //         'consumer_secret' => '',
        //         'access_token' => '',
        //         'access_secret' => '',
        //         'account_id' => '',
        //         'list_id' => '',
        //     ],
        //     // 'infusionsoft' => '',
        //     'markethero' => [
        //         'api_key' => '',
        //         'tag_id' => '',
        //     ],
        // ];


        // $stores = Store::all();

        // foreach($stores as $store){
        //     foreach ($autoresponders as $key => $autoresponder) {
        //         $existing = Autoresponder::where('store_id', $store->id)->where('name', $key)->first();
                
        //         if(!isset($existing)){
        //             Autoresponder::create([
        //                 'store_id' => $store->id,
        //                 'name' => $key,
        //                 'settings' => json_encode($autoresponder),
        //             ]);
        //         }
        //     }
        // }

        dd('die');

        // $data = [
        //     'consumer_key' => 'Ak21jMIZJjuFioK2iQ0IWhZT',
        //     'consumer_secret' => 'bYM13czLozYHrlYmd2OPMZStEiQXAWQBSXWKuDlV',
        // ];

        // $oauthString = $this->aweberRequestToken($data);
        // $tokens = $this->splitOauthString($oauthString);

        // Session::put('aweber_token', $tokens);

        // return redirect('https://auth.aweber.com/1.0/oauth/authorize?oauth_token=' . $tokens->oauth_token);

        // $accountId = $this->getAccounts($data)[0]->id;
        // $listId = $this->getLists($data, $accountId)[0]->id;

        $accountId = '227294';
        $listId = '3223670';

        $stack = HandlerStack::create();

        $middleware = new Oauth1([
            'consumer_key'    => $data['consumer_key'],
            'consumer_secret' => $data['consumer_secret'],
            'token'           => 'AgGEQgj1wvj5422x8p2SQzsG',
            'token_secret'    => 'Vvg6SV6CDvDOtG3ZHEmcVVkHVvrSkhJP9UavpTCO',
        ]);

        $stack->push($middleware);

        $client = new Client([
            'handler' => $stack
        ]);

        // Set the "auth" request option to "oauth" to sign using oauth
        $url = 'https://api.aweber.com/1.0/accounts/' . $accountId . '/lists/' . $listId . '/subscribers?ws.op=create';

        try{
            $response = $client->post($url, [
                'auth' => 'oauth',
                'content-type' => 'application/json',
                'json' => [
                    'email' => 'paulerickcampos08@gmail.com'
                ]
            ]);
            
            dd($response->getBody()->getContents());
        }catch(\Exception $e){
            dd($e->getResponse()->getBody()->getContents());
        }

        // $settings = [
        //     'consumer_key' => 'hl0C9EinHwLjZ3Uy760xsJAP9jBUDUcnCDhILqsG279KFCrdW5',
        //     'consumer_secret' => 'ef09gm4h4gcypgN9hTzCLqwRRobUPuk3cAafVGuuxANB9DHmS3',
        //     'oauth_token' => 'HcgJAdMvg8nvA5RURHwQQRZAgXpXNTck8GQNCr23ClzYHgvhSd',
        //     'oauth_secret' => 'oz5VOyhHIZChYk8x9ySFUJJAIUYcW78R4z3kEn2X4HwL7899lU',
        //     'blog_name' => "codeninjawarrior.tumblr.com",
        // ];
       
        // $title = "Kitchen Confidential";
        // $url = "https://scubba.instantecomlab.com/product/kitchen-confidential-updated-edition-adventures-in-the-culinary-underbelly-p-s-";
        // $thumbnail = "https://images-na.ssl-images-amazon.com/images/I/51%2BcWVVPuQL.jpg";
                
        // $params = [
        //     'title' => $title,
        //     'thumbnail' => $thumbnail,
        //     'url' => $url,
        //     'type' => 'link'
        // ];	

        // $headers = [
        //     'Host' => 'http://api.tumblr.com/',
        //     'Content-type' => 'application/x-www-form-urlencoded',
        //     'Expect' => ''
        // ];

        // $headers = $this->oauthGen("POST", "http://api.tumblr.com/v2/blog/" . $settings['blog_name'] . "/post", $params, $headers, $settings);

        // $ch = curl_init();
        // // curl_setopt($ch, CURLOPT_USERAGENT, "PHP Uploader Tumblr v1.0");
        // curl_setopt($ch, CURLOPT_URL, "http://api.tumblr.com/v2/blog/" . $settings['blog_name'] . "/post");
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );

        // curl_setopt($ch, CURLOPT_HTTPHEADER, [
        //     'Authorization: ' . $headers['Authorization'],
        //     'Content-type: ' . $headers['Content-type'],
        //     'Expect: '
        // ]);

        // $params = http_build_query($params);

        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        // $response = curl_exec($ch);
        // $json = json_decode($response,true);  
        // dd($json);         
    }

    public function aweberCallback(Request $request){
        $tokens = Session::get('aweber_token');
        $data = [
            'consumer_key' => 'Ak21jMIZJjuFioK2iQ0IWhZT',
            'consumer_secret' => 'bYM13czLozYHrlYmd2OPMZStEiQXAWQBSXWKuDlV',
            'oauth_token' => $request->oauth_token,
            'oauth_secret' => $tokens->oauth_token_secret,
            'oauth_verifier' => $request->oauth_verifier,
        ];

        $stack = HandlerStack::create();

        $middleware = new Oauth1([
            'consumer_key' => $data['consumer_key'],
            'consumer_secret' => $data['consumer_secret'],
            'client_key' => $data['consumer_key'],
            'client_secret'=> $data['consumer_secret'],
            'resource_owner_key' => $data['oauth_token'],
            'resource_owner_secret'=> $data['oauth_secret'],
            'verifier'=> $data['oauth_verifier'],
            'token' => $data['oauth_token'],
            'token_secret' => $data['oauth_secret'],
        ]);

        $stack->push($middleware);

        $client = new Client([
            // 'base_uri' => '',
            'handler' => $stack
        ]);

        // Set the "auth" request option to "oauth" to sign using oauth
        try{
            $response = $client->get('https://auth.aweber.com/1.0/oauth/access_token', ['auth' => 'oauth']);

            return json_encode($this->splitOauthString($response->getBody()->getContents()));
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    public function addSubscriber($apikey, $listId, $email){
        try{
            $client = new Client(['auth' => ['anystring', $apikey]]);
            $response = $client->request('POST', 
                'https://' . $this->getServer($apikey) . '.api.mailchimp.com/3.0/lists/' . $listId . '/members',
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

    public function listSubscribers($apikey, $listId){
        try{
            $client = new Client(['auth' => ['anystring', $apikey]]);
            $response = $client->request('GET', 'https://' . $this->getServer($apikey) . '.api.mailchimp.com/3.0/lists/' . $listId . '/members');
           
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e){
            return [];
        }
    }

    public function validateMailChimp($apikey){
        try{
            $client = new Client(['auth' => ['anystring', $apikey]]);
            $response = $client->request('GET', 'https://' . $this->getServer($apikey) . '.api.mailchimp.com/3.0/lists');
           
            return json_decode($response->getBody()->getContents())->lists;
        } catch (\Exception $e){
            return [];
        }
    }

    public function getServer($apikey){
        return explode('-', $apikey)[1];
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
    

    // public function validCredentials($affiliateSettings){
    //     foreach (json_decode($affiliateSettings->settings) as $setting) {
    //         if($setting == ""){
    //             return false;
    //             break;
    //         }
    //     }

    //     return true;
    // }

    // public function search($automation, $settings){
    //     $items = [];

    //     $query = [
    //         'apikey' => $settings->api_key,
    //         'publisherID' =>  $settings->publisher_id,
    //         'locale' => 'en_US',
    //         'perPage' => '50',
    //         'term' => $automation->keyword,
    //         'categoryId' => $this->getCategory($automation->category)
    //     ];


    //     for ($page = 1; $page <= 150; $page += 50) {
    //         $query['start'] = $page;
    //         $response = json_decode($this->doRequest($query));

    //         foreach ($response->products as $product) {
    //             array_push($items, $product);
    //         }

    //         usleep(500000);
    //     }

    //     return $items;
    // }

    // public function doRequest($query){
    //     $client = new Client();

    //     try {
    //         $response = $client->request(
    //             'GET', 'https://api.shop.com/AffiliatePublisherNetwork/v1/products',
    //             ['query' => $query]
    //         );
            
    //         return $response->getBody()->getContents();
    //     } catch (\Exception $e) {
    //         return [
    //             'error' => $e->getMessage()
    //         ];
    //     }
    // }

    // public function getCategory($category){
    //     $shopcom = [
    //         'All' => '',
    //         "Baby" => "1-32804",
    //         "Baby" => "1-32811",
    //         "Books" => "1-32836",
    //         "Beauty" => "1-32867",
    //         "Business" => "1-32820",
    //         "Cameras" => "1-32837",
    //         "Clothes" => "1-32838",
    //         "Computers" => "1-32862",
    //         "Collectibles" => "1-32839",
    //         "Crafts" => "1-32835",
    //         "Electronics" => "1-32863",
    //         "Food and Drink" => "1-32806",
    //         "Garden" => "1-32808",
    //         "Health & Nutrition" => "1-32841",
    //         "Home Store" => "1-32842",
    //         "Jewelry" => "1-32800",
    //         "Movies" => "1-32819",
    //         "Music" => "1-32812",
    //         "Party Supplies" => "1-32840",
    //         "Pet Supplies" => "1-32809",
    //         "Posters" => "1-32807",
    //         "Shoes" => "1-32805",
    //         "Software" => "1-32864",
    //         "Sports and Fitness" => "1-32844",
    //         "Sports Fan Shop" => "1-32877",
    //         "Travel" => "1-32813",
    //         "Tools" => "1-32843",
    //         "Toys" => "1-32810",
    //         "Video Games" => "1-32866",
    //     ];


    //     return $shopcom[$category];
    // }

    // public function validateProducts($items, $store, $limit, $product_data, $settings){
    //     $products = [];

    //     foreach ($items as $item) {
    //         $image = $this->getImage($item);

    //         if($image == "")
    //             continue;

    //         if(count($products) == $limit)
    //             break;
            
    //         $is_already_existing = Product::where('reference_id', trim($item->id))->where('store_id', $store->id)->count();

    //         if($is_already_existing == 0){
    //             $price = isset($item->minimumPrice) ? $item->minimumPrice : $item->maximumPrice;

    //             $product_details = [
    //                 'reference_id' => $item->id,
    //                 'name' => $item->name,
    //                 'description' => $item->description,
    //                 'permalink' => $this->clean($item->name),
    //                 'details_link' => $item->referralUrl,
    //                 'image' => $image,
    //                 'price' => (double)trim(str_replace('$', '', $price)),
    //                 'source' => 'shopcom',
    //                 'published_date' => $product_data->published_date,
    //                 'status' => $product_data->status,
    //                 'images' => []
    //             ];

    //             array_push($products, $product_details);
    //         }
    //     }

    //     return $products;
    // }
    
    // public function clean($string) {
    //     $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    //     $string = trim(preg_replace('/-+/', '-', $string), '-');
	// 	$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	// 	$string = strtolower($string); // Convert to lowercase
 
	// 	return $string;
    // }
    
    // public function getImage($item){
    //     $image = '';

    //     if(isset($item->imageUrl)){
    //         $image = $item->imageUrl;
    //     }

    //     return $image;
    // }
}
