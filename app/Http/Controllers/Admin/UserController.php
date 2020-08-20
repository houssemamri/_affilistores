<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\GlobalController;
use App\User;
use Auth;

class UserController extends GlobalController
{
    public function index(){
        $user = User::get()->first();
        return view('admin.users.index');
    }

    public function create(Request $request){
        
        if($request->isMethod('POST')){

        }

        return view('admin.users.create');
    }
}
