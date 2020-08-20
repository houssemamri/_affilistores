<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use Route;
use Purifier;
use Session;
use Crypt;
use App\FacebookCustomerChat;
use App\FacebookCommentPlugin;
use App\Store;
use App\ExitPop;

class IncreaseConversionController extends GlobalController
{
    private $store;

    public function __construct(){
        parent::__construct();
        
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();
    }

    public function facebookCustomerChat(Request $request){
        $chat = FacebookCustomerChat::where('store_id', $this->store->id)->first();

        if($request->isMethod('POST')){
            $this->validate($request, [
                'code_snippet' => 'required',
            ]);

            $chat->update([
                'code' => $request->code_snippet
            ]);

            Session::flash('success', 'Facebook customer chat code snippet updated successfully.');
            return redirect()->back();
        }

        return view('increase-conversion.facebook-chat-support', compact('chat'));
    }

    public function facebookCommentPlugin(Request $request){
        $comment = FacebookCommentPlugin::where('store_id', $this->store->id)->first();

        if($request->isMethod('POST')){
            $this->validate($request, [
                'code_sdk' => 'required',
                'code_snippet' => 'required',
            ]);

            $comment->update([
                'sdk_code' => $request->code_sdk,
                'code_snippet' => $request->code_snippet
            ]);

            Session::flash('success', 'Facebook Comment Plugin code snippet updated successfully.');
            return redirect()->back();
        }

        return view('increase-conversion.facebook-comment-plugin', compact('comment'));
    }

    public function exitPops(){
        $exitPops = ExitPop::where('store_id', $this->store->id)->get();

        return view('increase-conversion.exit-pops.index', compact('exitPops'));
    }

    public function exitPopsCreate(Request $request){
        $exitPops = ExitPop::where('store_id', $this->store->id)->get();
        
        if($request->isMethod('POST')){
            $this->validate($request, [
                'name' => 'required',
                'heading' => 'required',
                'body' => 'required',
                'button_text' => 'required',
                'email_content' => 'required',
            ]);

            if(isset($request->status)){
                $exitpops = ExitPop::where('store_id', $this->store->id)->update([
                    'status' => 0
                ]);
            }

            $image = isset($request->image) ? $this->uploadImage($request) : '';
            
            $style = [
                'heading' => [
                    'font-family' => $request->heading_font,
                    'font-size' => $request->heading_font_size,
                    'color' => $request->heading_font_color
                ],
                'body' => [
                    'font-family' => $request->body_font,
                    'font-size' => $request->body_font_size,
                    'color' => $request->body_font_color
                ],
                'button' => [
                    'background-color' => $request->button_color
                ],
            ];

            ExitPop::create([
                'store_id' => $this->store->id,
                'name' => $request->name,
                'heading' => $request->heading,
                'body' => $request->body,
                'image' => $image,
                'button_text' => $request->button_text,
                'content' => $request->email_content,
                'status' => isset($request->status) ? 1 : 0,
                'styles' => json_encode($style),
            ]);

            Session::flash('success', 'Exit pop ' . $request->name . ' added successfully.');
            return redirect()->route('exitpops.index', $this->store->subdomain);
        } 

        return view('increase-conversion.exit-pops.create', compact('exitPops'));
    }

    public function uploadImage(Request $request){
        $file = $request->file('image');
        $fileExtension = $file->getClientOriginalExtension();
        $fileName = 'IMG_' . time() . '.' . $fileExtension;

        $file->move('img/uploads/' . $this->store->subdomain . '/exit-pop', $fileName);

        return asset('img/uploads/' . $this->store->subdomain . '/exit-pop/' . $fileName);
    }

    public function exitPopsUpdate(Request $request, $subdomain, $id){
        $decrypted = Crypt::decrypt($id);
        $exitpop = ExitPop::find($decrypted);

        if($request->isMethod('POST')){
            $this->validate($request, [
                'name' => 'required',
                'heading' => 'required',
                'body' => 'required',
                'button_text' => 'required',
                'email_content' => 'required',
            ]);

            $status = isset($request->status) ? 1 : 0;

            if($status == 1){
                ExitPop::where('store_id', $this->store->id)->where('id', '<>', $exitpop->id)->update([
                    'status' => 0
                ]);
            }

            $image = isset($request->image) ? $this->uploadImage($request) : $exitpop->image;

            $style = [
                'heading' => [
                    'font-family' => $request->heading_font,
                    'font-size' => $request->heading_font_size,
                    'color' => $request->heading_font_color
                ],
                'body' => [
                    'font-family' => $request->body_font,
                    'font-size' => $request->body_font_size,
                    'color' => $request->body_font_color
                ],
                'button' => [
                    'background-color' => $request->button_color
                ],
                'img' => $image
            ];

            $exitpop->update([
                'name' => $request->name,
                'heading' => $request->heading,
                'body' => $request->body,
                'image' => $image,
                'button_text' => $request->button_text,
                'content' => $request->email_content,
                'status' => $status,
                'styles' => json_encode($style),
            ]);

            Session::flash('success', 'Exit pop ' . $request->name . ' updated successfully.');
            return redirect()->route('exitpops.index', $this->store->subdomain);
        } 

        return view('increase-conversion.exit-pops.edit', compact('id', 'exitpop'));
    }

    public function exitPopsDelete(Request $request){
        $decrypted = Crypt::decrypt($request->exitpop_id);
        $exitPop = ExitPop::find($decrypted);
        
        if($exitPop->status == 1){
            Session::flash('error', 'Exit pops is in use. Please make sure to set different exit pop to use.');
        }else{
            $exitPop->delete();
            Session::flash('success', 'Exit pop successfully deleted');
        }
        
        return redirect()->back();
    }
}
