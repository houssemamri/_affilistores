<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use Route;
use Purifier;
use Session;
use Crypt;
use File;
use Auth;
use App\Store;
use App\StoreLegalPage;
use App\StoreCategoryMenu;
use App\StoreBannerAd;
use App\StoreTheme;
use App\StoreSlider;
use App\Theme;
use App\ColorScheme;
use App\Slider;
use App\Category;
use App\AccessTheme;
use App\AccessColorScheme;
use App\AccessFeature;

class StoreDesignController extends GlobalController
{
    private $store;
    private $features;

    public function __construct(){
        parent::__construct();
        
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();
        $this->features = $this->getFeatures();
    }


    public function theme(Request $request){
        $store = $this->store;
        $storeTheme = StoreTheme::where('store_id', $store->id)->first();
        $themes = AccessTheme::where('membership_id', Auth::user()->memberDetail->membership->id)->get();
        $selectedColorSchemes = $this->selectedColorSchemes($storeTheme->theme_id);
        $colorSchemes = $this->colorSchemes();

        $pixabayCategories = [
            'all', 'fashion', 'nature', 'backgrounds', 'science', 'education', 'people', 'feelings', 'religion', 'health', 'places', 'animals', 'industry', 'food', 'computer', 'sports', 'transportation', 'travel', 'buildings', 'business', 'music'
        ];

        if($request->isMethod('POST')){
            $checkTheme = AccessTheme::where('membership_id', Auth::user()->memberDetail->membership->id)->where('theme_id', $request->theme)->first();
            $checkColorScheme = AccessColorScheme::where('membership_id', Auth::user()->memberDetail->membership->id)->where('color_scheme_id', $request->color_scheme)->first();
            
            if(!$checkTheme){
                Session::flash('error', 'Invalid theme selection');
                return redirect()->back();
            }elseif(!$checkColorScheme){
                Session::flash('error', 'Invalid color scheme selection');
                return redirect()->back();
            }

            $logo = $this->uploadLogo($request);
            $favicon =  $this->uploadFavicon($request);

            $storeTheme = StoreTheme::where('store_id', $this->store->id)->first();
            $storeTheme->update([
                'theme_id' => $request->theme,
                'color_scheme_id' => $request->color_scheme,
                'favicon' => $favicon,
            ]);

            $this->store->update([ 'logo' => $logo ]);

            Session::flash('success', 'Theme settings successfully updated');
            return redirect()->back();
        }

        return view('store-design.theme', compact('themes', 'selectedColorSchemes', 'colorSchemes', 'store', 'storeTheme', 'pixabayCategories'));
    }

    public function selectedColorSchemes($themeId){
        $selectedColorSchemes = [];

        $colorSchemes = ColorScheme::where('theme_id', $themeId)->get();

        foreach ($colorSchemes as $colorScheme) {
            $exist = AccessColorScheme::where('membership_id', Auth::user()->memberDetail->membership->id)->where('color_scheme_id', $colorScheme->id)->first();
            if(isset($exist))
                array_push($selectedColorSchemes, $colorScheme);
        }

        return $selectedColorSchemes;
    }

    public function colorSchemes(){
        $colorSchemes = [];

        $accessColorSchemes = AccessColorScheme::where('membership_id', Auth::user()->memberDetail->membership->id)->get();

        foreach ($accessColorSchemes as $accessColorScheme) {
            array_push($colorSchemes, $accessColorScheme->colorScheme);
        }

        return json_encode($colorSchemes);
    }

    public function uploadLogo($request){
        if(isset($request->logo)){
            $file = $request->file('logo');
            $fileExtension = $file->getClientOriginalExtension();
            $fileName =  'IMG_' . time() . '.' . $fileExtension;
            
            $file->move('img/uploads/'.$this->store->subdomain.'/'.'logo/', $fileName);
    
        }elseif(isset($request->pixabayLogo)){
            $file = file_get_contents($request->pixabayLogo);
            $fileExtension = explode('.', $request->pixabayLogo);
            $fileName =  'IMG_' . time() . '.' . $fileExtension[count($fileExtension) - 1];
            $path = 'img/uploads/'.$this->store->subdomain.'/logo/';

            if(!File::exists($path)) {
                File::makeDirectory($path, 0775, true);
            }

            file_put_contents($path . $fileName , $file); 
        }else{
            $fileName = $this->store->logo;
        }

        return $fileName;
    }

    public function uploadFavicon($request){
        if(isset($request->favicon)){
            $file = $request->file('favicon');
            $fileExtension = $file->getClientOriginalExtension();
            $fileName =  'FAV_ICN_' . time() . '.' . $fileExtension;
            
            $file->move('img/uploads/'.$this->store->subdomain.'/'.'logo/', $fileName);
    
        }elseif(isset($request->pixabayFavicon)){
            $file = file_get_contents($request->pixabayFavicon);
            $fileExtension = explode('.', $request->pixabayFavicon);
            $fileName =  'FAV_ICN_' . time() . '.' . $fileExtension[count($fileExtension) - 1];
            $path = 'img/uploads/'.$this->store->subdomain.'/logo/';

            if(!File::exists($path)) {
                File::makeDirectory($path, 0775, true);
            }

            file_put_contents($path . $fileName , $file); 
        }else{
            $fileName = $this->store->logo;
        }

        return $fileName;
    }


    // public function uploadImg($request, $type){
    //     $file = $request->file($type);
    //     $fileExtension = $file->getClientOriginalExtension();
    //     $preText = ($type == 'favicon') ? 'FAV_ICN_' : 'IMG_';
    //     $fileName =  $preText . time() . '.' . $fileExtension;
        
    //     $file->move('img/uploads/'.$this->store->subdomain.'/'.'logo/', $fileName);

    //     return $fileName;
    // }
    
    public function slider(Request $request){
        $sliders = StoreSlider::where('store_id', $this->store->id)->get();
        $store = $this->store;
        $pixabayCategories = [
            'all', 'fashion', 'nature', 'backgrounds', 'science', 'education', 'people', 'feelings', 'religion', 'health', 'places', 'animals', 'industry', 'food', 'computer', 'sports', 'transportation', 'travel', 'buildings', 'business', 'music'
        ];

        if($request->isMethod('POST')){
            $sliderId = Crypt::decrypt($request->sliderId);
            $slider = Slider::find($sliderId);

            $image = $this->uploadSliderImg($request, $slider);

            $slider->update([
                'main_tagline' => $request->main_tagline, 
                'main_tagline_font_size' => $request->main_tagline_font_size, 
                'sub_tagline' => $request->sub_tagline, 
                'sub_tagline_font_size' => $request->sub_tagline_font_size, 
                'cta_button_one_text' => $request->cta_button_one_text, 
                'cta_button_one_link' => $request->cta_button_one_link, 
                'cta_button_two_text' => $request->cta_button_two_text, 
                'cta_button_two_link' => $request->cta_button_two_link, 
                'image' => $image, 
                'status' => isset($request->status) ? 1 : 0 
            ]);

            Session::flash('success', 'Slider settings successfully updated');
            return redirect()->back();
        }

        return view('store-design.slider', compact('store', 'sliders', 'pixabayCategories'));
    }

    public function uploadSliderImg($request, $slider){
        if(isset($request->image)){
            $file = $request->file('image');
            $fileExtension = $file->getClientOriginalExtension();
            $fileName =  'SLD_' . time() . '.' . $fileExtension;
            
            $file->move('img/uploads/'.$this->store->subdomain.'/'.'slider/', $fileName);
    
        }elseif(isset($request->pixabayImage)){
            $file = file_get_contents($request->pixabayImage);
            $fileExtension = explode('.', $request->pixabayImage);
            $fileName =  'SLD_' . time() . '.' . $fileExtension[count($fileExtension) - 1];
            $path = 'img/uploads/'.$this->store->subdomain.'/slider/';

            if(!File::exists($path)) {
                File::makeDirectory($path, 0775, true);
            }

            file_put_contents($path . $fileName , $file); 
        }else{
            $fileName = $slider->image;
        }

        return $fileName;
    }

    public function bannerAd(Request $request){
        $bannerAds = StoreBannerAd::where('store_id', $this->store->id)->get();
        $features = $this->getFeatures();

        if($request->isMethod('POST')){
            $bannerAdsNoSelect = StoreBannerAd::where('store_id', $this->store->id)->where('type', '<>', 'MenuBanner')->where('type', '<>', 'MenuBannerAdSense')->update(['selected' => 0]);
            $bannerAd = StoreBannerAd::where('store_id', $this->store->id)->where('type', $request->banner_ad_type)->first();
            
            if($bannerAd->type == 'GoogleAdSense' && $request->banner_ad_type == 'GoogleAdSense'){
                $this->validate($request, [
                    'adsense_code' => 'required',
                ]);

                $code = [
                    'code' => $request->adsense_code
                ];

                $bannerAd->update([
                    'content' => json_encode($code),
                    'selected' => 1
                ]);
            }

            if($bannerAd->type == 'ImageUpload' && $request->banner_ad_type == 'ImageUpload'){
                $this->validate($request, [
                    'banner_link' => 'required',
                ]);

                $oldImg = json_decode($bannerAd->content)->banner_image;
                if($oldImg == "" && (!$request->hasFile('banner_image'))){
                    Session::flash('error', 'Banner image is a required field');
                    return redirect()->back();
                }

                $bannerImg = ($request->hasFile('banner_image')) ? $this->uploadBannerImg($request): $oldImg;
                $bannerImg = [
                    'banner_image' => $bannerImg,
                    'banner_link' => $request->banner_link
                ];

                $bannerAd->update([
                    'content' => json_encode($bannerImg),
                    'selected' => 1
                ]);
            }
            
            Session::flash('success', 'Banner Ads Settings successfully updated');
            return redirect()->back();
        }

        return view('store-design.banner-ad', compact('bannerAds', 'features'));
    }

    public function getFeatures(){
        $features = [];
        $accessFeatures = AccessFeature::where('membership_id', $this->store->user->memberDetail->membership->id)->get();
        
        foreach ($accessFeatures as $feature) {
            if($feature->features->type !== 'affiliate_store')
                array_push($features, strtolower(str_replace('.', '', $feature->features->name)));
        }

        return $features;
    }

    public function bannerAdMenu(Request $request){        
        if($request->isMethod('POST')){
            $bannerAd = StoreBannerAd::where('store_id', $this->store->id)->where('type', $request->menu_banner_ad_type)->first();
            $bannerAdsNoSelect = StoreBannerAd::where('store_id', $this->store->id)->whereIn('type', ['MenuBannerAdSense', 'MenuBanner'])->update(['selected' => 0]);

            if($bannerAd->type == 'MenuBannerAdSense' && $request->menu_banner_ad_type == 'MenuBannerAdSense'){
                $this->validate($request, [
                    'adsense_code' => 'required',
                ]);

                $code = [
                    'code' => $request->adsense_code
                ];

                $bannerAd->update([
                    'content' => json_encode($code),
                    'selected' => isset($request->selected) ? '1' : '0'
                ]);
            }

            if($bannerAd->type == 'MenuBanner' && $request->menu_banner_ad_type == 'MenuBanner'){
                $this->validate($request, [
                    'menu_link' => 'required',
                ]);
    
                $oldImg = json_decode($bannerAd->content)->image;
    
                if($oldImg == "" && (!$request->hasFile('menu_banner_image'))){
                    Session::flash('error', 'Menu Banner image is a required field');
                    return redirect()->back();
                }
    
                $bannerImg = ($request->hasFile('menu_banner_image')) ? $this->uploadMenuBannerImg($request): $oldImg;
    
                $content = [
                    'image' => $bannerImg,
                    'link' => $request->menu_link
                ];
    
                $bannerAd->update([
                    'content' => json_encode($content),
                    'selected' => isset($request->selected) ? '1' : '0'
                ]);
            }
            
            Session::flash('success', 'Menu Banner Ads successfully updated');
            return redirect()->back();
        }
    }
    
    public function uploadMenuBannerImg($request){
        $file = $request->file('menu_banner_image');
        $fileExtension = $file->getClientOriginalExtension();
        $fileName =  'BNNR_AD_' . time() . '.' . $fileExtension;

        $file->move('img/uploads/'.$this->store->subdomain.'/'.'bannerAd/', $fileName);

        return $fileName;
    }


    public function uploadBannerImg($request){
        $file = $request->file('banner_image');
        $fileExtension = $file->getClientOriginalExtension();
        $fileName =  'BNNR_AD_' . time() . '.' . $fileExtension;

        $file->move('img/uploads/'.$this->store->subdomain.'/'.'bannerAd/', $fileName);

        return $fileName;
    }

    public function legalPages(Request $request){
        $legalPage = StoreLegalPage::where('store_id', $this->store->id)->first();

        if($request->isMethod('POST')){
            $storeId = $this->store->id;

            if(isset($_POST['btn_terms'])){
                $this->validate($request, [
                    'terms_and_conditions' => 'required',
                ]);
                
                $legalPage->update([
                    'terms_conditions' => $request->terms_and_conditions
                ]);

                Session::flash('success', 'Terms and conditions successfully updated.');
            }
            
            if(isset($_POST['btn_privacy'])){
                $this->validate($request, [
                    'privacy_policy' => 'required',
                ]);

                $legalPage->update([
                    'privacy_policy' => $request->privacy_policy
                ]);

                Session::flash('success', 'Privacy policy successfully updated.');
            }

            if(isset($_POST['btn_contact_us'])){
                $this->validate($request, [
                    'contact_us' => 'required',
                ]);

                $legalPage->update([
                    'contact_us' => $request->contact_us
                ]);

                Session::flash('success', 'Contact us successfully updated.');
            }

            if(isset($_POST['btn_gdpr'])){
                $this->validate($request, [
                    'gdpr_compliance' => 'required',
                ]);

                $legalPage->update([
                    'gdpr_compliance' => $request->gdpr_compliance
                ]);

                Session::flash('success', 'GDPR Compliance successfully updated.');
            }

            if(isset($_POST['btn_affiliate_disclosure'])){
                $this->validate($request, [
                    'affiliate_disclosure' => 'required',
                ]);

                $legalPage->update([
                    'affiliate_disclosure' => $request->affiliate_disclosure
                ]);

                Session::flash('success', 'Affiliate Disclosure successfully updated.');
            }

            if(isset($_POST['btn_cookie_policy'])){
                $this->validate($request, [
                    'cookie_policy' => 'required',
                ]);

                $legalPage->update([
                    'cookie_policy' => $request->cookie_policy
                ]);

                Session::flash('success', 'Cookie Policy successfully updated.');
            }

            return redirect()->back();
        }

        return view('store-design.legal-pages', compact('legalPage'));
    }
    
    public function hasLegalPages($storeId){
        $legalPage = StoreLegalPage::where('store_id', $storeId)->first();

        if(isset($legalPage))
            return $legalPage;
        else
            return false;
    }

    public function categoryMenu(Request $request){
        $categories = Category::where('store_id', $this->store->id)->get();
        $store = $this->store;
        $menus = $this->store->categoryMenu->pluck('category_id')->toArray();

        if($request->isMethod('POST')){
            $storeCategoryMenu = StoreCategoryMenu::where('store_id', $this->store->id)->delete();

            $ctr = 1;
            foreach ($request->menu as $menu) {
                StoreCategoryMenu::create([
                    'store_id' => $this->store->id,
                    'category_id' => $menu,
                    'order' => $ctr
                ]);

                $ctr++;
            }

            Session::flash('success', 'Category menu successfully updated');
            return redirect()->back();
        }

        return view('store-design.category-menu', compact('categories', 'store', 'menus'));
    }

    public function footer(Request $request){
        $footerSettings = json_decode($this->store->storeTheme->footer_settings);
        $about = $footerSettings->about;
        $newsletter = $footerSettings->newsletter;

        if($request->isMethod('POST')){
            $this->validate($request, [
                'about' => 'required',
                'newsletterHeading' => 'required',
                'newsletterText' => 'required',
            ]);

            $footer_settings = [
                'about' => $request->about,
                'newsletter' => [
                    'heading' => $request->newsletterHeading,
                    'text' => $request->newsletterText
                ]
            ];

            $this->store->storeTheme->update([
                'footer_settings' => json_encode($footer_settings)
            ]);

            Session::flash('success', 'Footer settings successfully updated');
            return redirect()->back();
        }

        return view('store-design.footer-settings', compact('about', 'newsletter'));
    }
}
