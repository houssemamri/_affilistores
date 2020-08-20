<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\GlobalController;
use Input;
use Hash;
use Session;
use Crypt;
use Purifier;
use App\Bonus;
use App\BonusAvailability;
use App\Membership;
use App\MemberMenu;

class BonusController extends GlobalController
{
    public function index(){
        $bonuses = Bonus::all();
        return view('admin.bonuses.index', compact('bonuses'));
    }

    public function create(Request $request){
        $memberships = Membership::all();

        if($request->isMethod('POST')){
            // $this->validate($request, [
            //     'name' => 'required',
            //     'description' => 'required',
            //     'image' => 'required|max:2100',
            //     'product_file' => 'required|max:25000',
            // ]);

            if(!isset($request->available_for)){
                Session::flash('error', 'At least one membership must be selected');
                return redirect()->back()->withInput(Input::all());
            }

            if(isset($request->available_for) && count($request->available_for) == 0){
                Session::flash('error', 'At least one membership must be selected');
                return redirect()->back()->withInput(Input::all());
            }

            $bonusFile = $this->uploadFile($request);
            $coverImage = $this->uploadImage($request);

            $bonus = Bonus::create([
                'name' => $request->name,
                'description' => Purifier::clean(htmlspecialchars($request->description)),
                'image' => $coverImage,
                'file' => $bonusFile['file'],
                'size' => $bonusFile['size'],
            ]);

            foreach ($request->available_for as $membership) {
                BonusAvailability::create([
                    'membership_id' => $membership,
                    'bonus_id' => $bonus->id,
                ]);
            }
            
            return response()->json(['sucess' => true, 'msg' => 'Bonus successfully created', 'url' => route('bonuses.index')]);
            // Session::flash('success', 'Bonus successfully created');
            // return redirect()->route('bonuses.index');
        }
        
        return view('admin.bonuses.create', compact('memberships'));
    }

    public function uploadFile($request){
        $file = $request->file('product_file');
        $fileExtension = $file->getClientOriginalExtension();
        $fileSize = ($file->getSize() * 0.000001) . ' MB';
        $fileName =  'BNS_' . time() . '.' . $fileExtension;
        $file->move('bonuses', $fileName);

        return ['file' => $fileName, 'size' => $fileSize];
    }

    public function uploadImage($request){
        $file = $request->file('image');
        $fileExtension = $file->getClientOriginalExtension();
        $fileName =  'CVR_' . time() . '.' . $fileExtension;
        
        $file->move('bonuses', $fileName);

        return $fileName;
    }

    public function edit(Request $request, $id){
        $memberships = Membership::all();
        $decrypted = Crypt::decrypt($id);
        $bonus = Bonus::find($decrypted);

        if($request->isMethod('POST')){
            // $this->validate($request, [
            //     'name' => 'required',
            //     'description' => 'required',
            //     'image' => 'max:2100',
            //     'product_file' => 'max:25000',
            // ]);

            if(!isset($request->available_for)){
                Session::flash('error', 'At least one membership must be selected');
                return redirect()->back()->withInput(Input::all());
            }

            if(isset($request->available_for) && count($request->available_for) == 0){
                Session::flash('error', 'At least one membership must be selected');
                return redirect()->back()->withInput(Input::all());
            }

            $coverImage = isset($request->image) ? $this->uploadImage($request) : $bonus->image; 

            if(isset($request->product_file)){
                $upload = $this->uploadFile($request);
                $file = $upload['file'];
                $size = $upload['size'];
            }else{
                $file = $bonus->file;
                $size = $bonus->size;
            }

            $bonus->update([
                'name' => $request->name,
                'description' => Purifier::clean(htmlspecialchars($request->description)),
                'image' => $coverImage,
                'file' => $file,
                'size' => $size,
            ]);

            //remove old available_for
            $bonusAvailability = BonusAvailability::where('bonus_id', $bonus->id)->delete();

            foreach ($request->available_for as $membership) {
                BonusAvailability::create([
                    'membership_id' => $membership,
                    'bonus_id' => $bonus->id,
                ]);
            }

            return response()->json(['sucess' => true, 'msg' => 'Bonus successfully updated', 'url' => route('bonuses.index')]);
            // Session::flash('success', 'Bonus successfully updated');
            // return redirect()->route('bonuses.index');
        }
        
        return view('admin.bonuses.edit', compact('memberships', 'bonus', 'id'));
    }

    public function delete(Request $request){
        $decrypted = Crypt::decrypt($request->bonus_id);
        $bonus = Bonus::find($decrypted);
        $bonusAvailability = BonusAvailability::where('bonus_id', $bonus->id)->delete();
        $bonus->delete();

        Session::flash('success', 'Bonus ' .$bonus->title. ' successfully deleted');
        return redirect()->route('bonuses.index');
    }
}
