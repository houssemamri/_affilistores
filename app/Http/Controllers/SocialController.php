<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Controllers\GlobalController;
use Input;
use Session;
use SimpleXMLElement;
use Route;
use Crypt;
use Purifier;
use Response;
use Thujohn\Twitter\Facades\Twitter;
use Carbon\Carbon;
use App\Store;
use App\SocialCampaign;
use App\Campaign;
use App\Category;
use App\ProductCategory;
use App\Product;
use App\SocialSetting;

class SocialController extends GlobalController
{
    private $store;

    public function __construct(){
        parent::__construct();
        
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();
    }

    public function index(){
        $socialCampaigns = SocialCampaign::where('store_id', $this->store->id)->get();
        $socialCredentials = $this->socialCredentials();

        return view('social.index', compact('socialCampaigns', 'socialCredentials'));
    }

    public function create(Request $request){
        $categories = Category::where('store_id', $this->store->id)->get();
        $socialCredentials = $this->socialCredentials();
        $twitter = $this->getTwitterSettings();
        $facebook = $this->getFacebookSettings();
        $tumblr = $this->getTumblrSettings();
        $pinterest = $this->getPinterestSettings();
        $sampleProduct = Product::first();

        if($request->isMethod('POST')){
            $this->validate($request, [
                'automation_name' => 'required',
                'category' => 'required',
                'schedule_date' => 'required',
                'schedule_time' => 'required',
            ]);

            if(!isset($request->products) || count($request->products) == 0){
                Session::flash('error', 'Please select atleast one product to share');
                return redirect()->back()->withInput($request->input());
            }   

            if(!isset($request->type) || count($request->type) == 0){
                Session::flash('error', 'Please select atleast one social media platform');
                return redirect()->back()->withInput($request->input());
            }
            
            $socialCampaign = SocialCampaign::create([
                'store_id' => $this->store->id,
                'name' => $request->automation_name,
                'category_id' => $request->category,
                'enable_autopost' => 1,
                'schedule_date' => $request->schedule_date,
                'schedule_time' => $request->schedule_time,
                'products' => json_encode($request->products)
            ]);

            foreach ($request->type as $social) {
                Campaign::create([
                    'store_id' => $this->store->id,
                    'social_campaign_id' => $socialCampaign->id,
                    'type' => $social,
                ]);
            }

            Session::flash('success', 'Social campaign successfully added');
            return redirect()->route('social.index', $this->store->subdomain);
        }

        return view('social.create', compact('categories', 'socialCredentials', 'twitter', 'facebook', 'tumblr', 'pinterest', 'sampleProduct'));
    }

    public function edit(Request $request, $subdomain, $id){
        $decrypted = Crypt::decrypt($id);
        $categories = Category::where('store_id', $this->store->id)->get();
        $socialCampaign = SocialCampaign::find($decrypted);
        $socialCredentials = $this->socialCredentials();
        $products = ProductCategory::where('category_id', $socialCampaign->category_id)->get();
        $twitter = $this->getTwitterSettings();
        $facebook = $this->getFacebookSettings();
        $tumblr = $this->getTumblrSettings();
        $pinterest = $this->getPinterestSettings();
        $sampleProduct = Product::first();

        if($request->isMethod('POST')){
            $this->validate($request, [
                'automation_name' => 'required',
                'category' => 'required',
            ]);

            $socialCampaign->update([
                'name' => $request->automation_name,
                'category_id' => $request->category,
                'products' => json_encode($request->products)
            ]);

            $socialCampaign->campaigns()->delete();

            if(isset($request->type)){
                foreach ($request->type as $social) {
                    if($socialCampaign->campaigns->where('type', $social)->count() == 0){
                        Campaign::create([
                            'store_id' => $this->store->id,
                            'social_campaign_id' => $socialCampaign->id,
                            'type' => $social,
                        ]);
                    }
                }
            }

            Session::flash('success', 'Social campaign successfully updated');
            return redirect()->route('social.index', $this->store->subdomain);
        }

        return view('social.edit', compact('socialCampaign', 'id', 'categories', 'socialCredentials', 'products', 'facebook', 'twitter', 'tumblr', 'pinterest', 'sampleProduct'));
    }

    public function delete(Request $request){
        $decrypted = Crypt::decrypt($request->social_id);
        $socialCampaign = SocialCampaign::find($decrypted);
        $socialCampaign->campaigns()->delete();

        if($socialCampaign->delete()){
            Session::flash('success', 'Social campaign successfully deleted');
            return redirect()->back();
        }
    }

    public function socialCredentials(){
        $socialSettings = $this->store->socialSettings;
        $socials = [];
        
        foreach ($socialSettings as $socialSetting) {
            $settings = json_decode($socialSetting->settings);
            foreach ($settings as $setting) {
                if($setting == ""){
                    $socials[$socialSetting->name] = false;    
                    break;
                }else{
                    $socials[$socialSetting->name] = true;    
                }
            }
    
        }

        return $socials;
    }

    public function getTwitterSettings(){
        return $this->store->socialSettings->where('name', 'twitter')->first()->settings;
    }

    public function getFacebookSettings(){
        return $this->store->socialSettings->where('name', 'facebook')->first()->settings;
    }

    public function getTumblrSettings(){
        return $this->store->socialSettings->where('name', 'tumblr')->first()->settings;
    }

    public function getPinterestSettings(){
        return $this->store->socialSettings->where('name', 'pinterest')->first()->settings;
    }
}
