<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use Session;
use DateTime;
use Crypt;
use Auth;
use File;
use Response;
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
use App\ProductReview;
use App\ProductBlog;

class CloneStoreController extends GlobalController
{
    public function index(){
        $stores = Auth::user()->stores;

        return view('clone.index', compact('stores'));
    }

    public function export(Request $request, $subdomain, $id){
        $store = Store::where('id', Crypt::decrypt($id))->first();
        $data = $this->getAllSettings($store);
        $path = 'img/uploads/'.$store->subdomain.'/json/';

        if(!File::exists($path))
            mkdir($path, 0777, true);
        
        $fileName = $store->subdomain .'_'. time() . '_settings.json';
        $filePath = $path . $fileName;

        File::put($filePath, json_encode($data));

        return Response::download($filePath);
    }

    public function getAllSettings($store){
        $data = [
            'affiliate_settings' => $store->affiliateSettings,
            'automations' => $store->automations,
            'blogs' => [
                'list' => $store->blogs,
                'categories' => $store->blogCategories,
                'feeds' => $store->blogFeeds
            ],
            'social_campaigns' => [
                'list' => $store->socialCampaigns,
                'campaigns' => $store->storeCampaigns,
                'logs' => $store->socialCampaignLogs
            ],
            'categories' => $store->categories,
            'smtp_settings' => $store->smtp,
            'contact_us_messages' => [],
            'exit_pops' => $store->exitPops,
            'facebook_comment_plugin' => $store->facebookCommentPlugin,
            'facebook_customer_chat' => $store->facebookChatSupport,
            'get_response_settings' => $store->getResponseSetting,
            'products' => [
                'list' => $store->products,
                'categories' => ProductCategory::whereIn('product_id', $store->products()->pluck('id')->toArray())->get(),
                'tags' => ProductTag::whereIn('product_id', $store->products()->pluck('id')->toArray())->get(),
                'images' => ProductImage::whereIn('product_id', $store->products()->pluck('id')->toArray())->get(),
                'videos' => ProductVideo::whereIn('product_id', $store->products()->pluck('id')->toArray())->get(),
                'seo_settings' => ProductSeoSetting::whereIn('product_id', $store->products()->pluck('id')->toArray())->get(),
                'tweets' => ProductTweet::whereIn('product_id', $store->products()->pluck('id')->toArray())->get(),
                'hits' => ProductHit::whereIn('product_id', $store->products()->pluck('id')->toArray())->get(),
                'reviews' => ProductReview::whereIn('product_id', $store->products()->pluck('id')->toArray())->get(),
                'blog' => ProductBlog::whereIn('product_id', $store->products()->pluck('id')->toArray())->get(),
            ],
            'product_tracks' => $store->productTracks,
            'seo_settings' => [
                'analytics' => $store->analytics,
                'archive_page' => $store->archivePage,
                'home_page' => $store->homePage,
                'product_page' => $store->productPage,
                'title_settings' => $store->titleSettings,
                'webmaster_settings' => $store->webmasterSettings
            ],
            'smo_settings' => $store->smo,
            'social_proofs' => $store->socialProofs,
            'social_settings' => $store->socialSettings,
            'store_banner' => $store->bannerAd,
            'store_category_menu' => $store->categoryMenu,
            'store_legal_pages' => $store->legalPage,
            'store_slider' => [
                'list' => $store->storeSliders,
                'sliders' => $this->getSliders($store),
            ],
            'store_social_proof_setting' => $store->socialProofSetting,
            'store_theme' => $store->storeTheme,
            'subscribers' => $store->subscribers,
            'subscription_mail' => [],
            'subscription_sent' => [],
            'tags' => $store->tags
        ];

        return $data;
    }

    public function getSliders($store){
        $sliders = [];
        $sliderIds = StoreSlider::where('store_id', $store->id)->pluck('slider_id')->toArray();
        foreach ($sliderIds as $key => $value) 
            array_push($sliders, Slider::where('id', $value)->first());
    
        return $sliders;
    }

    public function import(Request $request, $subdomain, $id){
        $store = Store::where('id', Crypt::decrypt($id))->first();
        $stores =  Auth::user()->stores->where('id', '<>', $store->id);

        return view('clone.import', compact('id', 'store'));
    }
}
