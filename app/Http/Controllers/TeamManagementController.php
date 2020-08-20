<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use Input;
use Hash;
use Session;
use Crypt;
use Auth;
use App\User;
use App\Access;
use App\AccessRight;
use App\UserDetail;
use App\Role;
use App\Store;
use App\MemberSubuserDetail;

class TeamManagementController extends GlobalController
{
    public function index(){
        $users = User::where('role_id', 5)->get();
        return view('settings.team-management.index', compact('users'));
    }

    public function addUser(Request $request){
        $stores = Store::where('user_id', Auth::user()->id)->get();

        if($request->isMethod('POST')){
            $this->validate($request, [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'confirm_password' => 'required'
            ]);
                
            if(!isset($request->store)){
                Session::flash('error', 'Please choose atleast one store to manage');
                return redirect()->back()->withInput(Input::all());
            }

            if($request->password == $request->confirm_password){
                $unique = UserDetail::where('first_name', $request->first_name)->where('last_name', $request->last_name)->count();
                if($unique == 0){
                    $input = Input::all();

                    $user = User::create([
                        'name' => $request->first_name.' '. $request->last_name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'role_id' => 5,
                        'active' => 1,
                    ]);

                    $user_detail = UserDetail::create([
                        'user_id' => $user->id,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                    ]);
                    
                    //inser store id with access rights
                    if($this->manageAccessRights($user, $request)){
                        Session::flash('success', 'Successfully added new user');
                        return redirect()->route('teamManagement');
                    }
                  
                    Session::flash('success', 'Successfully added new user');
                    return redirect()->route('teamManagement');
                }else{
                    Session::flash('error', 'First name and Last name already existing.');
                    return redirect()->back()->withInput(Input::all());
                }
            }else{
                Session::flash('error', 'Password did not match');
                return redirect()->back()->withInput(Input::all());
            }
        }

        return view('settings.team-management.create-user', compact('stores'));
    }

    public function editUser(Request $request, $id){
        $decrypted = Crypt::decrypt($id);
        $user = User::find($decrypted);
        $stores = Store::where('user_id', Auth::user()->id)->get();

        if($request->isMethod('POST')){
            if(isset($_POST['update_password'])){
                $this->validate($request, [
                    'old_password' => 'required',
                    'new_password' => 'required',
                    'confirm_password' => 'required',
                ]);
        
                if(Hash::check($request->old_password, $user->password)){
                    if($request->new_password == $request->confirm_password){
                        
                        $user->password = Hash::make($request->new_password);
                        $user->save();

                        Session::flash('success', $user->detail->first_name . '\'s password successfully updated!');
                    }else{
                        Session::flash('error', 'Password did not match');
                    }
                }else{
                   Session::flash('error', 'Incorrect Password!');
                }

                return redirect()->route('teamManagement');
            }else{
                if($request->isMethod('POST')){
                    $this->validate($request, [
                        'first_name' => 'required',
                        'last_name' => 'required',
                    ]);
                        
                    if(!isset($request->store)){
                        Session::flash('error', 'Please choose atleast one store to manage');
                        return redirect()->back()->withInput(Input::all());
                    }
        
                    $unique = UserDetail::where('first_name', $request->first_name)->where('last_name', $request->last_name)->where('user_id', '<>', $decrypted)->count();

                    if($unique == 0){
                        $input = Input::all();
    
                        $user->update([
                            'name' => $request->first_name.' '. $request->last_name,
                        ]);
                        
                        $userDetail = UserDetail::where('user_id', $user->id)->first();

                        $userDetail->update([
                            'first_name' => $request->first_name,
                            'last_name' => $request->last_name,
                        ]);
                        
                        if($this->manageAccessRights($user, $request)){
                            Session::flash('success', 'Successfully added new user');
                            return redirect()->route('teamManagement');
                        }
                    }else{
                        Session::flash('error', 'First name and Last name already existing.');
                        return redirect()->back()->withInput(Input::all());
                    }
                   
                }

                Session::flash('success', 'User '. $userDetail->first_name .' successfully updated!');
                return redirect()->route('teamManagement');
            }
        }

        return view('settings.team-management.edit-user', compact('user', 'stores', 'id'));
    }
    
    public function deleteUser(Request $request){
        $decrypted = Crypt::decrypt($request->user_id);
        $user = User::find($decrypted);
        $user->delete();
        $userDetail = UserDetail::where('user_id', $decrypted)->first();
        $userDetail->delete();

        $accessRights = AccessRight::where('user_id', $decrypted)->get();
        foreach ($accessRights as $accessRight) {
            $accessRight->delete();
        }

        Session::flash('success', 'User '. $userDetail->first_name .' successfully deleted!');
        return redirect()->route('teamManagement');
    }

    public function manageAccessRights($user, $request){
        $accessRights = AccessRight::where('user_id', $user->id)->get();
        foreach ($accessRights as $accessRight) {
            $accessRight->delete();
        }
        
        //inser store id with access rights
        $accesses = Access::all();

        foreach ($request->store as $store) {
            foreach ($accesses as $access) {
                AccessRight::create([
                    'user_id' => $user->id,
                    'store_id' => $store,
                    'access_id' => $access->id
                ]);
            }
        }

        return true;
    }
    public function getCurrentStore(){
        $store = Store::where('subdomain', Session::get('subdomain'))->first();
        
        return $store;
    }
}
