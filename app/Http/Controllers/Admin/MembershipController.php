<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\GlobalController;
use GuzzleHttp\Client;
use Input;
use Hash;
use Session;
use Crypt;
use Purifier;
use App\Membership;
use App\MemberDetail;
use App\MembershipFeature;
use App\MemberMenu;
use App\Feature;
use App\Theme;
use App\ColorScheme;
use App\AccessRight;
use App\AccessFeature;
use App\AccessTheme;
use App\AccessColorScheme;
use App\PageAvailability;
use App\BonusAvailability;
use App\Setup;

class MembershipController extends GlobalController
{
    public function index(){
        $memberships = Membership::all();

        return view('admin.memberships.index', compact('memberships'));
    }

    public function create(Request $request){
        $menus = MemberMenu::all();
        $features = Feature::all();
        $themes = Theme::all();
        $colorSchemes = ColorScheme::all();
        $memberships = Membership::all();
        
        if($request->isMethod('POST')){
            $this->validate($request, [
                'title' => 'required',
                'jvzoo_product_id' => 'required',
            ]);

            if(!isset($request->access_rights) || count($request->access_rights) <= 0){
                Session::flash('error', 'Please select atleast one access rights');
                return redirect()->back()->withInput($request->all());
            }elseif(!isset($request->schemes) || count($request->schemes) <= 0){
                Session::flash('error', 'Please select atleast one template and color scheme');
                return redirect()->back()->withInput($request->all());
            }elseif(!isset($request->affiliateStores) || count($request->affiliateStores) <= 0){
                Session::flash('error', 'Please select atleast one affiliate store');
                return redirect()->back()->withInput($request->all());
            }
            
            $membership = Membership::create([
                'title' => $request->title,
                'jvzoo_product_id' => $request->jvzoo_product_id,
                'upgrade_membership_url' => $request->upgrade_membership_url,
                'next_upgrade_membership_id' => $request->next_upgrade_membership_id,
                'product_price' => $request->product_price,
                'frequency' => $this->getFrequency($request->frequency),
                'trial_period' => $request->trial_period,
                'trial_price' => $request->trial_price,
                'stores_per_month' => $request->stores_per_month,
            ]);

            $request->features = isset($request->features) ? $request->features : [];
            
            foreach ($request->features as $features) {
                MembershipFeature::create([
                    'membership_id' => $membership->id,
                    'feature' => $features
                ]);
            }

            foreach ($request->access_rights as $rights) {
                AccessRight::create([
                    'member_menu_id' => $rights,
                    'membership_id' => $membership->id,
                ]);
            }

            $request->schemes = count($request->schemes) > 0 ? $request->schemes : [];

            foreach ($request->schemes as $scheme) {
                AccessColorScheme::create([
                    'color_scheme_id' => $scheme,
                    'membership_id' => $membership->id,
                ]);
                
                $themeId = ColorScheme::find($scheme)->theme_id;

                $checkAccesTheme = AccessTheme::find($themeId);

                if(!isset($checkAccesTheme)){
                    AccessTheme::create([
                        'membership_id' => $membership->id, 
                        'theme_id' => $themeId
                    ]);
                }
            }

            $request->extraFeatures = isset($request->extraFeatures) ? $request->extraFeatures : [];

            foreach ($request->extraFeatures as $extraFeature) {
                AccessFeature::create([
                    'feature_id' => $extraFeature,
                    'membership_id' => $membership->id,
                ]);
            }

            $request->affiliateStores = isset($request->affiliateStores) ? $request->affiliateStores : [];

            foreach ($request->affiliateStores as $affiliateStore) {
                AccessFeature::create([
                    'feature_id' => $affiliateStore,
                    'membership_id' => $membership->id,
                ]);
            }

            Session::flash('success', 'Successfuly created membership');
            return redirect()->route('memberships.index');
        }

        return view('admin.memberships.create', compact('menus', 'themes', 'colorSchemes', 'features', 'memberships'));
    }

    public function getFrequency($frequency){
        $months = 0;

        if($frequency == 'monthly'){
            $months = 1;
        }elseif($frequency == 'quarterly'){
            $months = 3;
        }elseif($frequency == 'yearly'){
            $months = 12;
        }elseif($frequency == 'lifetime'){
            $months = 120;
        }else{
            $months = 1;
        }

        return $months;
    }

    public function edit(Request $request, $id){
        $decrypted = Crypt::decrypt($id);
        $membership = Membership::find($decrypted);
        $memberships = Membership::all();
        $menus = MemberMenu::all();
        $features = Feature::all();
        $themes = Theme::all();
        $colorSchemes = ColorScheme::all();

        if($request->isMethod('POST')){
            $this->validate($request, [
                'title' => 'required',
                'jvzoo_product_id' => 'required',
            ]);

            if(count($request->access_rights) == 0){
                Session::flash('error', 'Please select atleast one access rights');
                return redirect()->back()->withInput($request->all());
            }elseif(!isset($request->schemes) || count($request->schemes) <= 0){
                Session::flash('error', 'Please select atleast one template and color scheme');
                return redirect()->back()->withInput($request->all());
            }elseif(!isset($request->affiliateStores) || count($request->affiliateStores) <= 0){
                Session::flash('error', 'Please select atleast one affiliate store');
                return redirect()->back()->withInput($request->all());
            }elseif($membership->id == $request->next_upgrade_membership_id){
                Session::flash('error', 'Membership cannot be the same as next upgrade membeship');
                return redirect()->back()->withInput($request->all());
            }

            $membership->update([
                'title' => $request->title,
                'jvzoo_product_id' => $request->jvzoo_product_id,
                'upgrade_membership_url' => $request->upgrade_membership_url,
                'next_upgrade_membership_id' => $request->next_upgrade_membership_id,
                'product_price' => $request->product_price,
                'frequency' => $this->getFrequency($request->frequency),
                'trial_period' => $request->trial_period,
                'trial_price' => $request->trial_price,
                'stores_per_month' => $request->stores_per_month,
            ]);

            if(isset($request->features)){
                $fearures_deleted = MembershipFeature::where('membership_id', $membership->id)->delete();
             
                foreach ($request->features as $features) {
                    MembershipFeature::create([
                        'membership_id' => $membership->id,
                        'feature' => $features
                    ]);
                }
            }
            
            $accessRights = AccessRight::where('membership_id', $membership->id)->delete();

            foreach ($request->access_rights as $rights) {
                AccessRight::create([
                    'member_menu_id' => $rights,
                    'membership_id' => $membership->id,
                ]);
            }

            $request->schemes = count($request->schemes) > 0 ? $request->schemes : [];
            $accessColorSchemes = AccessColorScheme::where('membership_id', $membership->id)->delete();
            $accessThemes = AccessTheme::where('membership_id', $membership->id)->delete();

            foreach ($request->schemes as $scheme) {
                AccessColorScheme::create([
                    'color_scheme_id' => $scheme,
                    'membership_id' => $membership->id,
                ]);
                
                $themeId = ColorScheme::find($scheme)->theme_id;
                $checkAccesTheme = AccessTheme::where('membership_id', $membership->id)->where('theme_id', $themeId)->first();

                if(!isset($checkAccesTheme)){
                    AccessTheme::create([
                        'membership_id' => $membership->id, 
                        'theme_id' => $themeId
                    ]);
                }
            }

            $accessFeatures = AccessFeature::where('membership_id', $membership->id)->delete();
            $request->extraFeatures = isset($request->extraFeatures) ? $request->extraFeatures : [];

            foreach ($request->extraFeatures as $extraFeature) {
                AccessFeature::create([
                    'feature_id' => $extraFeature,
                    'membership_id' => $membership->id,
                ]);
            }

            $request->affiliateStores = isset($request->affiliateStores) ? $request->affiliateStores : [];

            foreach ($request->affiliateStores as $affiliateStore) {
                AccessFeature::create([
                    'feature_id' => $affiliateStore,
                    'membership_id' => $membership->id,
                ]);
            }

            Session::flash('success', 'Successfuly updated membership');
            return redirect()->route('memberships.index');
        }

        return view('admin.memberships.edit', compact('membership', 'id', 'menus', 'features', 'themes', 'colorSchemes', 'memberships'));
    }

    public function delete(Request $request){
        $decrypted = Crypt::decrypt($request->membership_id);
        $membership = Membership::find($decrypted);
        $checkUserMember = MemberDetail::where('membership_id', $membership->id)->count();

        if($checkUserMember == 0){
            $pageAvailability = PageAvailability::where('membership_id', $membership->id)->delete();
            $bonusAvailability = BonusAvailability::where('membership_id', $membership->id)->delete();
            $membershipFeatures = MembershipFeature::where('membership_id', $membership->id)->delete();
            $accessRights = AccessRight::where('membership_id', $membership->id)->delete();
            $accessThemes = AccessTheme::where('membership_id', $membership->id)->delete();
            $accessColorSchemes = AccessColorScheme::where('membership_id', $membership->id)->delete();
            $accessFeatures = AccessFeature::where('membership_id', $membership->id)->delete();
            $membership->delete();

            Session::flash('success', 'Successfully deleted '. $membership->title . ' membership');
        }else{
            Session::flash('error', 'Membership cannot be deleted. Members are connected to this membership.');
        }

        return redirect()->route('memberships.index');
    }
}
