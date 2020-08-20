<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use View;
use Auth;
use App\Setup;
use App\MemberNotification;
use App\MemberNotificationView;
use App\Page;
use App\MemberMenu;
use App\PageAvailability;
use App\AccessFeature;

class GlobalController extends Controller
{
    protected $user;

    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            View::share('site', $this->site());
            View::share('newNotifications', $this->getNotifications());
            View::share('topNavPages', $this->getTopNavPages());
            View::share('footerPages', $this->getFooterNavPages());
            View::share('sidenavPages', $this->getSideNavPages());
            View::share('sidenavNoParentPages', $this->getNoParentPages());
            View::share('user', $this->user);
            View::share('accessFeatures', $this->getAccessFeatures($this->user));
            View::share('sideMenus', $this->getSideMenu($this->user));

            return $next($request);
        });
    }  

    public function site(){
        $setups = Setup::all();
        $site = [];
        foreach ($setups as $setup) {
            $site[$setup->key] = $setup->value;
        }

        return $site;
    }

    public function getNotifications(){
        if(!isset(Auth::user()->id) || Auth::user()->role->id == 1 || Auth::user()->role->id == 6)
            return 0;

        $countNotifications = 0;
        $notifications = MemberNotification::all();
        $openedNotifications = MemberNotificationView::where('user_id', Auth::user()->id)->where('is_open', 1)->pluck('member_notification_id')->toArray();
        
        foreach ($notifications as $notification) {

            if(!(in_array($notification->id, $openedNotifications)))
                $countNotifications++;
        }

        return $countNotifications;
    }

    public function getTopNavPages(){
        if(!isset(Auth::user()->id) || Auth::user()->role->id == 1 || Auth::user()->role->id == 6)
            return [];

        $availables = PageAvailability::where('membership_id', Auth::user()->memberDetail->membership_id)->get();
        $pages = $this->availablePages($availables, 'top_nav');

        return $pages;
    }

    public function getFooterNavPages(){
        if(!isset(Auth::user()->id) || Auth::user()->role->id == 1 || Auth::user()->role->id == 6)
            return [];

        $availables = PageAvailability::where('membership_id', Auth::user()->memberDetail->membership_id)->get();
        $pages = $this->availablePages($availables, 'footer_nav');
        return $pages;
    }

    public function getSideNavPages(){
        if(!isset(Auth::user()->id) || Auth::user()->role->id == 1 || Auth::user()->role->id == 6)
            return [];
            
        $availables = PageAvailability::where('membership_id', Auth::user()->memberDetail->membership_id)->get();
        $menus = MemberMenu::all();
        $sideMenus = [];
        
        foreach ($menus as $menu) {            
            $sideMenus[$menu->slug] = [];
            foreach ($availables as $available) {
                if($available->page->page_part == 'side_nav' && $menu->id == $available->page->menu_id){
                    array_push($sideMenus[$menu->slug], $available->page);
                }
            }
        }
        
        ksort($sideMenus);
        return $sideMenus;
    }

    public function getNoParentPages(){
        if(!isset(Auth::user()->id) || Auth::user()->role->id == 1 || Auth::user()->role->id == 6)
            return [];

        $availables = PageAvailability::where('membership_id', Auth::user()->memberDetail->membership_id)->get();
        $pages = [];
        
        foreach ($availables as $available) {
            if($available->page->page_part == 'side_nav' && $available->page->type == 1 && $available->page->menu_id == null){
                array_push($pages, $available->page);
            }
        }


        return $pages;
    }

    public function availablePages($availables, $type){
        $pages = [];

        foreach ($availables as $available) {
            if($available->page->page_part == $type && $available->page->type == 1){
                $pages[$available->page->order] = $available->page;
            }
        }
        ksort($pages);
        return $pages;
    }

    public function getAccessFeatures($user){
        if(!isset(Auth::user()->id) || Auth::user()->role->id == 1 || Auth::user()->role->id == 6)
            return [];

        $features = [];
        $accessFeatures = AccessFeature::where('membership_id', $user->memberDetail->membership->id)->get();
        
        foreach ($accessFeatures as $feature) {
            if($feature->features->type !== 'affiliate_store')
                array_push($features, strtolower(str_replace('.', '', $feature->features->name)));
        }

        return $features;
    }

    public function getSideMenu($user){
        if(isset($user) && $user->role->id == 3){
            $menuIds = $user->memberDetail->membership->accessRights()->pluck('member_menu_id')->toArray();
            return MemberMenu::whereIn('id', $menuIds)->orderBy('order', 'ASC')->get();
        }else{
            return [];
        }
        
    }
}
