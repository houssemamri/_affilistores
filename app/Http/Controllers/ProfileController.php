<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use Auth;
use Hash;
use Session;
use App\User;
use App\UserDetail;

class ProfileController extends GlobalController
{
    public function index(){
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function updateProfile(Request $request){
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $user = User::find(Auth::user()->id);
        $userDetails = UserDetail::where('user_id', Auth::user()->id)->first();

        $user->name = $request->first_name.' '.$request->last_name;
        $user->save();

        $avatar = ($request->file('avatar') !== null) ? $this->uploadAvatar($request): $user->detail->avatar;

        $userDetails->first_name = $request->first_name;
        $userDetails->last_name = $request->last_name;
        $userDetails->address = $request->address;
        $userDetails->phone = $request->phone;
        $userDetails->country = $request->country;
        $userDetails->state = $request->state;
        $userDetails->city = $request->city;
        $userDetails->avatar = $avatar;
        $userDetails->save();

        Session::flash('success', 'Profile successfully updated!');
        return redirect()->route('profile');
    }

    public function uploadAvatar(Request $request){
        $file = $request->file('avatar');
        $fileExtension = $file->getClientOriginalExtension();
        $fileName = 'AVATAR_' . time() . '.' . $fileExtension;

        $file->move('img/uploads/avatar/', $fileName);

        return $fileName;
    }

    public function updatePassword(Request $request){
        $this->validate($request, [
            'new_password' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
        ]);

        $user = User::where('id', Auth::user()->id)->first();
        
        if(Hash::check($request->password, $user->password)){
            if($request->new_password == $request->confirm_password){
                $user->password = Hash::make($request->new_password);
                $user->save();
                Session::flash('success', 'Password successfully updated!');
            }else{
                Session::flash('error', 'Password did not match');
            }
        }else{
           Session::flash('error', 'Incorrect Password!');
        }

        return redirect()->route('profile');
    }
}
