<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'user_id', 'name', 'subdomain', 'logo', 'status', 'featured'
    ];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function storeTheme(){
        return $this->hasOne('App\StoreTheme');
    }

    public function bannerAd(){
        return $this->hasMany('App\StoreBannerAd');
    }

    public function categories(){
        return $this->hasMany('App\Category');
    }

    public function affiliateSettings(){
        return $this->hasMany('App\AffiliateSetting');
    }

    public function socialSettings(){
        return $this->hasMany('App\SocialSetting');
    }

    public function businessProfile(){
        return $this->hasOne('App\ContactUsProfile');
    }

    public function facebookChatSupport(){
        return $this->hasOne('App\FacebookCustomerChat');
    }

    public function facebookCommentPlugin(){
        return $this->hasOne('App\FacebookCommentPlugin');
    }

    public function smo(){
        return $this->hasMany('App\SmoSetting');
    }

    public function legalPage(){
        return $this->hasOne('App\StoreLegalPage');
    }

    public function categoryMenu(){
        return $this->hasMany('App\StoreCategoryMenu');
    }

    public function homePage(){
        return $this->hasOne('App\SeoSettingsHomePage');
    }

    public function productPage(){
        return $this->hasOne('App\SeoSettingsProductPage');
    }

    public function archivePage(){
        return $this->hasOne('App\SeoSettingsArchivePage');
    }

    public function titleSettings(){
        return $this->hasOne('App\SeoSettingsTitleSetting');
    }
    
    public function webmasterSettings(){
        return $this->hasOne('App\SeoSettingsWebmastersSetting');
    }

    public function analytics(){
        return $this->hasOne('App\SeoSettingsAnalytic');
    }

    public function socialCampaigns(){
        return $this->hasMany('App\SocialCampaign');
    }

    public function socialCampaignLogs(){
        return $this->hasMany('App\SocialCampaignLog');
    }

    public function storeCampaigns(){
        return $this->hasMany('App\Campaign');
    }
    
    public function automations(){
        return $this->hasMany('App\Automation');
    }

    public function products(){
        return $this->hasMany('App\Product');
    }

    public function productHits(){
        return $this->hasMany('App\ProductHit');
    }

    public function pageHits() {
        return $this->productHits()->where('page_hits','>', 0);
    }

    public function affiliateHits() {
        return $this->productHits()->where('affiliate_hits','>', 0);
    }

    public function productTracks(){
        return $this->hasMany('App\ProductTrack');
    }

    public function contactMessages(){
        return $this->hasMany('App\ContactUsMessage');
    }

    public function smtp(){
        return $this->hasOne('App\ContactSmtp');
    }

    public function blogs(){
        return $this->hasMany('App\Blog');
    }

    public function blogCategories(){
        return $this->hasMany('App\BlogCategory');
    }

    public function blogFeeds(){
        return $this->hasMany('App\BlogFeed');
    }

    public function socialProofs(){
        return $this->hasMany('App\SocialProof');
    }

    public function socialProofSetting(){
        return $this->hasOne('App\StoreSocialProofSetting');
    }

    public function getResponseSetting(){
        return $this->hasOne('App\GetResponseSetting');
    }

    public function exitPops(){
        return $this->hasMany('App\ExitPop');
    }

    public function storeSliders(){
        return $this->hasMany('App\StoreSlider');
    }

    public function subscribers(){
        return $this->hasMany('App\Subscriber');
    }

    public function tags(){
        return $this->hasMany('App\Tag');
    }
}
