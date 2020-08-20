<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use GuzzleHttp\Client;
use Mail;
use Session;
use Route;
use Purifier;
use Crypt;
use App\Store;
use App\Subscriber;
use App\SubscriptionMail;
use App\SubscriptionSent;
use App\Setup;
use App\GetResponseSetting;

class SubscribeController extends GlobalController
{

    private $store;

    public function __construct(){
        parent::__construct();
        
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();
    }

    public function index(){
        $newsletters = SubscriptionMail::where('store_id', $this->store->id)->get();
        $responders = $this->getResponders();

        return view('subscriber.index', compact('newsletters', 'responders'));
    }

    public function getResponders(){
        return [
            // 'getresponse' => 'Get Response',
            'aweber' => 'AWeber',
            'mailchimp' => 'MailChimp',
            // 'infusionsoft' => 'Infusion Soft',
            'markethero' => 'Market Hero',
        ];
    }

    public function subscribers(){
        $subscribers = Subscriber::where('store_id', $this->store->id)->get();
        return view('subscriber.list', compact('subscribers'));
    }

    public function deleteSubscribers(Request $request){
        $subscriber = Subscriber::where('id', Crypt::decrypt($request->subscriber_id))->first();

        if(isset($subscriber)){
            $subscriber->delete();     
            Session::flash('success', 'Subscriber successfully deleted.');
        }else{
            Session::flash('error', 'Oops something went wrong! Invalid subscriber id.');
        }

        return redirect()->back();
    }

    public function create(Request $request){
        if($request->isMethod('POST')){
            $this->validate($request, [
                'subject' => 'required',
                'content' => 'required',
            ]);

            $mail= SubscriptionMail::create([
                'store_id' => $this->store->id,
                'subject' => $request->subject,
                'body' => Purifier::clean(htmlspecialchars($request->content))
            ]);

            if(isset($_POST['btn_save_sent'])){
                $this->send($this->store->subdomain, $mail->id);
                Session::flash('success', 'Newsletter successfully saved and sent');
            }else{
                Session::flash('success', 'Newsletter successfully saved');
            }

            return redirect()->route('newsletters.index', $this->store->subdomain);
        }

        return view('subscriber.create');
    }

    public function send($subdomain, $mailId){
        $subscribers = Subscriber::where('store_id', $this->store->id)->get();
        $mail= SubscriptionMail::find($mailId);
        $store = $this->store;

        foreach ($subscribers as $subscriber) {
            Mail::send([], [], function ($message) use ($subscriber, $mail, $store) {
                $message->from(env('MAIL_USERNAME'), $store->name);
                $message->to($subscriber->email);
                $message->subject($mail->subject);
                $message->setBody($mail->body, 'text/html');
            });

            SubscriptionSent::create([
                'store_id' => $this->store->id,
                'subscriber_id' => $subscriber->id,
                'subscription_mail_id' => $mail->id,
            ]);
        }

        Session::flash('success', 'Newsletter successfully saved and sent');
        return redirect()->route('newsletters.index', $this->store->subdomain);
    }

    public function delete(Request $request){
        $decrypted = Crypt::decrypt($request->newsletter_id);
        $newsletter = SubscriptionMail::find($decrypted);
        $sentNewsletter = SubscriptionSent::where('subscription_mail_id', $newsletter->id)->delete();
        $newsletter->delete();

        Session::flash('success', 'Newsletter deleted successfully');
        return redirect()->back();
    }

    public function getResponseUpdate(Request $request){
        $settings = $this->store->getResponseSetting;

        if($request->isMethod('POST')){
            $this->validate($request, [
                'api_key' => 'required',
                'campaign_name' => 'required'
            ]);

            $validation = $this->getResponseValidaton($request);

            if(isset($validation->accountId)){
                $getCampaign = $this->getResponseGetCampaign($request);

                if(!empty($getCampaign)){
                    $data = [
                        'api_key' => $request->api_key,
                        'campaign_id' => $getCampaign[0]->campaignId,
                        'campaign_name' => $getCampaign[0]->name,
                    ];

                    $settings->update([
                        'settings' => json_encode($data)
                    ]);

                    Session::flash('success', 'GetResponse Informations successfully saved.');
                }else{
                    Session::flash('error', 'Campaign not found.');
                }
            }else{
                Session::flash('error', "Invalid API Key.");
            }

            return redirect()->back();
        }

        return view('subscriber.getresponse.edit', compact('settings'));
    }

    public function getResponseValidaton($request){
        try{
            $client = new Client();

            $response = $client->request('GET', 'https://api.getresponse.com/v3/accounts', [
                'headers' => [
                    'X-Auth-Token' => 'api-key ' . trim($request->api_key),
                    'Content-Type'     => 'application/json',
                ]
            ]);
            
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getResponseGetCampaign($request){
        try{
            $client = new Client();

            $response = $client->request('GET', 'https://api.getresponse.com/v3/campaigns?query[name]=' . $request->campaign_name, [
                'headers' => [
                    'X-Auth-Token' => 'api-key ' . trim($request->api_key),
                    'Content-Type'     => 'application/json',
                ]
            ]);
            
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getResponseSync(){
        $settings = $this->store->getResponseSetting;
        $subscribers = Subscriber::where('store_id', $this->store->id)->get();
        $ctr = 0;

        if($this->validCredentials($settings)){
            foreach ($subscribers as $subscriber) {
                $addEmail = $this->addEmailtoCampaign($subscriber->email, json_decode($settings->settings));
                $ctr = !isset($addEmail) ? $ctr + 1 : $ctr;
            }

            Session::flash('success', $ctr . ' Subcribers successfully sync to contacts.');
        }else{
            Session::flash('error', 'Invalid api and campaign credentials.');
        }

        return redirect()->back();
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
}
