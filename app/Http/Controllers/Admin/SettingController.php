<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\GlobalController;
use GuzzleHttp\Client;
use Alaouy\Youtube\Facades\Youtube;
use Input;
use Hash;
use Session;
use Crypt;
use Purifier;
use App\MemberMenu;
use App\MemberNotification;
use App\MemberNotificationView;
use App\EmailResponder;
use App\Setup;
use App\YoutubeKey;

class SettingController extends GlobalController
{
    public function general(Request $request){
        $setups = Setup::all();

        if($request->isMethod('POST')){
            $this->validate($request, [
                'site_name' => 'required'
            ]);

            foreach ($setups as $setup) {
                if($setup->key == 'api_key' || $setup->key == 'campaign_name' || $setup->key == 'campaign_id') continue;

                if($setup->key == 'logo'){
                    $logo = ($request->file('logo')) ? $this->upload($request, 'logo'): $setup->value;
                    $setup->update([
                        'value' => $logo
                    ]);
                }elseif($setup->key == 'favicon'){
                    $favicon = ($request->file('favicon')) ? $this->upload($request, 'favicon'): $setup->value;
                    $setup->update([
                        'value' => $favicon
                    ]);
                }else{
                    $data = ($setup->type == 'textarea') ? Purifier::clean(htmlspecialchars($request->{$setup->key})) : ($request->{$setup->key});

                    $setup->update([
                        'value' => $data
                    ]);
                }
            }

            if(isset($request->api_key)){
                $this->validate($request, [
                    'campaign_name' => 'required'
                ]);

                $getResponse = $this->saveGetReponseSettings($request);

                if($getResponse['success'] == true){
                    $apiKey = Setup::where('key', 'api_key')->first()->update([ 'value' => $getResponse['data']['api_key']]);
                    $campaignName = Setup::where('key', 'campaign_name')->first()->update([ 'value' => $getResponse['data']['campaign_name']]);
                    $campaignId = Setup::where('key', 'campaign_id')->first()->update([ 'value' => $getResponse['data']['campaign_id']]);

                    Session::flash('success', 'General setting successfully updated');
                }else{
                    Session::flash('error', $getResponse['msg']);
                }
            }else{
                Session::flash('success', 'General setting successfully updated');
            }

            return redirect()->back();
        }

        return view('admin.settings.index', compact('setups'));
    }

    public function saveGetReponseSettings($request){
        $validation = $this->getResponseValidaton($request);
        $success = false;
        $data = null;

        if(isset($validation->accountId)){
            $getCampaign = $this->getResponseGetCampaign($request);

            if(!empty($getCampaign)){
                $data = [
                    'api_key' => $request->api_key,
                    'campaign_id' => $getCampaign[0]->campaignId,
                    'campaign_name' => $getCampaign[0]->name,
                ];
               
                $success = true;
                $msg = 'Successfully saved.';
            }else{
                $msg = 'GetResponse Campaign not found.';
            }
        }else{
            $msg = 'Invalid GetResponse API Key.';
        }

        return [
            'success' => $success,
            'msg' => $msg,
            'data' => $data
        ];
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

    public function upload(Request $request, $type){
        if($type == 'logo')
            $file = $request->file('logo');
        else
            $file = $request->file('favicon');

        $fileExtension = $file->getClientOriginalExtension();
        $preText = ($type == 'logo') ? 'IMG_' : 'FAVICON_';
        $fileName = $preText . time() . '.' . $fileExtension;

        $file->move('img/uploads/', $fileName);

        return $fileName;
    }

    public function menu(Request $request){
        $menus = MemberMenu::orderBy('order', 'ASC')->get();

        if($request->isMethod('POST')){
            if(count(array_filter($request->title)) != 10 || count(array_filter($request->icon)) != 10){
                Session::flash('error', 'Oops something went wrong! Input all icons and menu title');
                return redirect()->back();
            }

            $ctr = 0;
            foreach ($request->id as $id) {
                $decrpyted = Crypt::decrypt($id);
                $menu = MemberMenu::find($decrpyted);

                $menu->update([
                    'title' => $request->title[$ctr],
                    'icon' => $request->icon[$ctr],
                    'order' => $ctr + 1
                ]);

                $ctr++;
            }

            Session::flash('success', 'Successfully updated member\'s menu');
            return redirect()->back();
        }

        return view('admin.settings.menu', compact('menus'));
    }

    public function notification(){
        $notifications = MemberNotification::all();

        return view('admin.settings.notifications.index', compact('notifications'));
    }

    public function noificationCreate(Request $request){
        if($request->isMethod('POST')){
            $this->validate($request, [
                'subject' => 'required',
                'content' => 'required',
            ]);

            MemberNotification::create([
                'subject' => $request->subject,
                'body' => Purifier::clean(htmlspecialchars($request->content))
            ]);

            Session::flash('success', 'Successfully send notification');
            return redirect()->route('settings.notification.index');
        }
        return view('admin.settings.notifications.create');
    }

    public function notificationEdit(Request $request, $id){
        $decrpyted = Crypt::decrypt($id);
        $notification = MemberNotification::find($decrpyted);

        if($request->isMethod('POST')){
            $notification->update([
                'subject' => $request->subject,
                'body' => Purifier::clean(htmlspecialchars($request->content))
            ]);

            Session::flash('success', 'Successfully updated notification');
            return redirect()->route('settings.notification.index');
        }

        return view('admin.settings.notifications.edit', compact('id', 'notification'));
    }

    public function notificationDelete(Request $request){
        $decrpyted = Crypt::decrypt($request->notification_id);
        $notification = MemberNotification::find($decrpyted);

        $notificationViews = MemberNotificationView::where('member_notification_id', $notification->id)->delete();
        $notification->delete();

        Session::flash('success', 'Successfully deleted notification');
        return redirect()->route('settings.notification.index');
    }

    public function emailResponder(Request $request){
        $emailResponders = EmailResponder::all();

        if($request->isMethod('POST')){
            if(count(array_filter($request->from)) != 4 || count(array_filter($request->reply)) != 4 || count(array_filter($request->to)) != 4 || count(array_filter($request->subject)) != 4 || count(array_filter($request->content)) != 4){
                Session::flash('error', 'Please fill in all required fields!');
                return redirect()->back();
            }

            $ctr = 0;
            foreach ($request->id as $id) {
                $decrpyted = Crypt::decrypt($id);
                $emailResponder = EmailResponder::find($decrpyted);
                $emailResponder->update([
                    'from' => $request->from[$ctr],
                    'reply' => $request->reply[$ctr],
                    'to' => $request->to[$ctr],
                    'subject' => $request->subject[$ctr],
                    'body' =>  Purifier::clean(htmlspecialchars($request->content[$ctr]))
                ]);

                $ctr++;
            }

            Session::flash('success', 'Email responder settings successfully updated');
            return redirect()->back();
        }
        
        return view('admin.settings.email-responder', compact('emailResponders'));
    }

    public function youtubeKeys(Request $request){
        $keys = YoutubeKey::all();

        if($request->isMethod('POST')){

            if(!isset($request->api_keys)){
                Session::flash('error', 'Please enter atleast one YouTube API Key.');
            }else{
                $failedKeys = [];

                foreach ($request->api_keys as $key) {
                    if($this->validateYoutubeKey($key)){
                        YoutubeKey::updateOrCreate([
                            'api_key' => $key
                        ]);
                    }else{
                        array_push($failedKeys, $key);
                    }
                }

                if(count($failedKeys) > 0){
                    Session::flash('error', 'Some API key are invalid key/s: ' . implode(',', $failedKeys));
                }else{
                    Session::flash('success', 'API Keys successfully saved.');
                }
            }

            return redirect()->back();
        }

        return view('admin.settings.youtube_keys', compact('keys'));
    }

    public function validateYoutubeKey($key){
        Youtube::setApiKey($key);

        $params = [
            'q'             => 'android',
            'type'          => 'video',
            'part'          => 'id, snippet',
            'maxResults'    => 3
        ];

        try{
            return Youtube::searchAdvanced($params, true);
        } catch(\Exception $e){
            return null;
        }
    }
}
