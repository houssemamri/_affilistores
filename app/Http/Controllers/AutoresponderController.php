<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Session;
use Auth;
use Crypt;
use Route;
use App\Autoresponder;
use App\Store;

class AutoresponderController extends GlobalController
{
    private $store;

    public function __construct(){
        parent::__construct();
        
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();
    }

    public function create(Request $request, $subdomain, $autoresponder){
        if(in_array($autoresponder, $this->getAutoresponders())){
            $settings = Autoresponder::where('store_id', $this->store->id)->where('name', $autoresponder)->first();
            $data = json_decode($settings->settings);

            if($request->isMethod('POST')){
                if($autoresponder == 'mailchimp'){
                    $this->validate($request, [
                        'api_key' => 'required'
                    ]);

                    $validate = $this->validateMailChimp($request->api_key);

                    if(!empty($validate)){
                        $mailchimp = [
                            'api_key' => $request->api_key,
                            'list_id' => isset($request->list) ? $request->list : '',
                            'lists' => $validate,
                        ];

                        $settings->update([
                            'settings' => json_encode($mailchimp)
                        ]);

                        Session::flash('success', 'Mailchimp API settings successfully saved.');
                    }else{
                        Session::flash('error', 'Mailchimp API Key is incorrect');
                    }
                }elseif($autoresponder == 'aweber'){
                    $this->validate($request, [
                        'consumer_key' => 'required',
                        'consumer_secret' => 'required',
                    ]);

                    $requestData = ['consumer_key' => $request->consumer_key, 'consumer_secret' => $request->consumer_secret];

                    if(isset($data->access_token) && isset($data->access_secret)){
                        $data->list_id = $request->list;
                        
                        $settings->update([
                            'settings' => json_encode($data)
                        ]);

                        Session::flash('success', 'AWeber API Settings successfully saved.');

                    }else{
                        $requestToken = $this->aweberRequestToken($requestData);

                        if(isset($requestToken) && !empty($requestToken)){
                            $tokens = $this->splitOauthString($requestToken);
                            Session::put('aweber_keys', (object) $requestData);
                            Session::put('aweber_token', $tokens);
    
                            return redirect('https://auth.aweber.com/1.0/oauth/authorize?oauth_token=' . $tokens->oauth_token);
                        }else{
                            Session::flash('error', 'AWeber Keys are invalid.');
                        }
                    }
                }elseif($autoresponder == 'markethero'){
                    $this->validate($request, [
                        'api_key' => 'required'
                    ]);

                    $validate = $this->validateMarketHero($request->api_key);

                    if(!empty($validate)){
                        $marketHero = [
                            'api_key' => $request->api_key,
                            'tag_id' => isset($request->tag) ? $request->tag : '',
                            'tags' => $validate,
                        ];

                        $settings->update([
                            'settings' => json_encode($marketHero)
                        ]);

                        Session::flash('success', 'Market Hero API settings successfully saved.');
                    }else{
                        Session::flash('error', 'Market Hero API Key is incorrect');
                    }
                }

                return redirect()->back();
            }

            return  view('autoresponder.' . $autoresponder, compact('settings', 'autoresponder', 'data'));
        }else{
            Session::flash('error', 'Oops something went wrong! Invalid autoresponder app.');
            return redirect()->route('newsletters.index', $this->store->subdomain);
        }
    }

    public function validateMarketHero($apikey){
        try{
            $client = new Client();
            $response = $client->request('GET', 'http://api.markethero.io/v1/api/tags?apiKey=' . $apikey);
           
            return json_decode($response->getBody()->getContents())->tags;
        } catch (\Exception $e){
            return [];
        }
    }

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
            $response = $client->get('request_token?oauth_callback=' . route('aweber.callback', $this->store->subdomain) , ['auth' => 'oauth']);

            $oauth = $response->getBody()->getContents();
        }catch(\Exception $e){
            // dd($e);
        }

        return $oauth;
    }

    public function aweberCallback(Request $request){
        $settings = Autoresponder::where('store_id', $this->store->id)->where('name', 'aweber')->first();
        $keys = Session::get('aweber_keys');
        $tokens = Session::get('aweber_token');
        $access = null;

        $data = [
            'consumer_key' => $keys->consumer_key,
            'consumer_secret' => $keys->consumer_secret,
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

        try{
            $response = $client->get('https://auth.aweber.com/1.0/oauth/access_token', ['auth' => 'oauth']);

            $access =  $this->splitOauthString($response->getBody()->getContents());
        }catch(\Exception $e){
            Session::flash('error', $e->getMessage());
            return redirect()->route('autoresponder.create', [$this->store->subdomain, 'aweber']);
        }


        if(isset($access)){
            $data['access_token'] = $access->oauth_token;
            $data['access_secret'] = $access->oauth_token_secret;
            $data['account_id'] = $this->getAweberAccounts($data)[0]->id;
            $data['lists'] = $this->getAweberLists($data);
            $data['list_id'] = '';

            $settings->update([
                'settings' => json_encode($data)
            ]);

            Session::flash('success', 'AWeber API Settings succcessfully set up.');
        }else{
            Session::flash('error', 'AWeber API Settings are invalid.');
        }

        return redirect()->route('autoresponder.create', [$this->store->subdomain, 'aweber']);
    }

    public function getAweberLists($data){
        $stack = HandlerStack::create();

        $middleware = new Oauth1([
            'consumer_key'    => $data['consumer_key'],
            'consumer_secret' => $data['consumer_secret'],
            'token'           => $data['access_token'],
            'token_secret'    => $data['access_secret'],
        ]);

        $stack->push($middleware);

        $client = new Client([
            'handler' => $stack
        ]);

        // Set the "auth" request option to "oauth" to sign using oauth

        try{
            $response = $client->get('https://api.aweber.com/1.0/accounts/' . $data['account_id'] . '/lists?ws.start=0&ws.size=10', ['auth' => 'oauth']);
            return json_decode($response->getBody()->getContents())->entries;
        }catch(\Exception $e){
            return [];
        }
    }

    public function getAweberAccounts($data){
        $stack = HandlerStack::create();

        $middleware = new Oauth1([
            'consumer_key'    => $data['consumer_key'],
            'consumer_secret' => $data['consumer_secret'],
            'token'           => $data['access_token'],
            'token_secret'    => $data['access_secret'],
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

    public function splitOauthString($string){
        $tokens = [];

        $string = explode('&', $string);

        foreach($string as $value){
            $value = explode('=', $value);

            $tokens[$value[0]] = $value[1];
        }

        return (object) $tokens;
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

    public function listMailChimpSubscribers($apikey, $listId){
        try{
            $client = new Client(['auth' => ['anystring', $apikey]]);
            $response = $client->request('GET', 'https://' . $this->getMailChimpServer($apikey) . '.api.mailchimp.com/3.0/lists/' . $listId . '/members');
           
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e){
            return [];
        }
    }

    public function validateMailChimp($apikey){
        try{
            $client = new Client(['auth' => ['anystring', $apikey]]);
            $response = $client->request('GET', 'https://' . $this->getMailChimpServer($apikey) . '.api.mailchimp.com/3.0/lists');
           
            return json_decode($response->getBody()->getContents())->lists;
        } catch (\Exception $e){
            return [];
        }
    }

    public function getMailChimpServer($apikey){
        return explode('-', $apikey)[1];
    }

    public function getAutoresponders(){
        return [
            'mailchimp', 'aweber', 'infusionsoft', 'markethero'
        ];
    }
}
