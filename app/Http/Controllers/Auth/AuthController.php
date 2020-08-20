<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GlobalController;
use Auth;
use Hash;
use Session;
use Input;
use Mail;
use App\Audit;
use App\Setup;
use App\EmailResponder;
use App\UserDetail;
use App\User;
use App\Store;

class AuthController extends GlobalController
{
    public function adminLogin(Request $request){
        $loginMsg = Setup::where('key', 'login_message')->first();
    
        if($request->isMethod('POST')){
            $this->validate($request, [
                'email' => 'required',
                'password' => 'required',
            ],
            [
                'email.required' => "The email field is required.",
                'password.required' => "The password field is required.",
            ]);
            
            $credentials = [
                'email' => $request->get('email'),
                'password' => $request->get('password')
            ];

            if(Auth::attempt($credentials)){
                if(Auth::user()->active == 0){
                    Session::flash('login_error', 'Account is deactivated please contact your admin.');
                    return redirect()->back()->withInput(Input::except("password"));
                }else{
                    if(Auth::user()->role->id == 1){
                        return redirect()->route('admin.dashboard');
                    }else{
                        Session::flash('login_error', 'Unauthorized access. Keep out!');
                        return redirect()->back()->withInput(Input::except("password"));
                    }
                }
            }else{
                Session::flash('login_error', 'Invalid email or password!');
                return redirect()->back()->withInput(Input::except("password"));
            }
        }

        return view('admin.login', compact('loginMsg'));
    }

    public function subAdminLogin(Request $request){
        $loginMsg = Setup::where('key', 'login_message')->first();
    
        if($request->isMethod('POST')){
            $this->validate($request, [
                'email' => 'required',
                'password' => 'required',
            ],
            [
                'email.required' => "The email field is required.",
                'password.required' => "The password field is required.",
            ]);
            
            $credentials = [
                'email' => $request->get('email'),
                'password' => $request->get('password')
            ];

            if(Auth::attempt($credentials)){
                if(Auth::user()->active == 0){
                    Session::flash('login_error', 'Account is deactivated please contact your admin.');
                    return redirect()->back()->withInput(Input::except("password"));
                }else{
                    if(Auth::user()->role->id == 6){
                        return redirect()->route('subadmin.dashboard');
                    }else{
                        Session::flash('login_error', 'Unauthorized access. Keep out!');
                        return redirect()->back()->withInput(Input::except("password"));
                    }
                }
            }else{
                Session::flash('login_error', 'Invalid email or password!');
                return redirect()->back()->withInput(Input::except("password"));
            }
        }

        return view('subadmin.login', compact('loginMsg'));
    }

    public function login(Request $request){
        $loginMsg = Setup::where('key', 'login_message')->first();
        
        if(Auth::guest()){
            if($request->isMethod('POST')){
                $this->validate($request, [
                    'email' => 'required',
                    'password' => 'required',
                ],
                [
                    'email.required' => "The email field is required.",
                    'password.required' => "The password field is required.",
                ]);
                
                $credentials = [
                    'email' => $request->get('email'),
                    'password' => $request->get('password')
                ];

                if(Auth::attempt($credentials)){
                    if(Auth::user()->active == 0){
                        Session::flash('login_error', 'Invalid email or password!');
                        return redirect()->back()->withInput(Input::except("password"));
                    }else{

                        if(Auth::user()->role->id == 1){
                            Auth::logout();
                            Session::flush();
                            
                            Session::flash('login_error', 'Invalid email or password!');
                            return redirect()->route('login')->withInput(Input::except("password"));
                        }else{
                            $store = $this->getFirstStore(Auth::user());

                            if(Auth::user()->role->id == 3 && isset(Auth::user()->memberDetail)){
                                $today = date('Y-m-d H:i:s');

                                if(!isset(Auth::user()->memberDetail->expiry_date)){
                                    Audit::create([
                                        'user_id' => Auth::user()->id,
                                        'type' => 'login',
                                        'action' => 'user log in'
                                    ]);

                                    Auth::user()->update([
                                        'last_login' => date('Y-m-d H:i:s')
                                    ]);

                                    if(is_null($store)){
                                        $userId = Auth::user()->id;
                                        $storeName = isset(Store::orderBy('id', 'DESC')->first()->id) ? ('store' . (Store::orderBy('id', 'DESC')->first()->id + 1)) : 'store1';
                                        Session::put('first_store', (object)['user_id' => $userId, 'store_name' => $storeName]);

                                        return redirect()->route('createFirstStore');
                                    }else{
                                        return redirect()->route('dashboard', ['subdomain' => $store->subdomain]);
                                    }

                                }elseif(isset(Auth::user()->memberDetail->expiry_date) && $today < Auth::user()->memberDetail->expiry_date){
                                    Audit::create([
                                        'user_id' => Auth::user()->id,
                                        'type' => 'login',
                                        'action' => 'user log in'
                                    ]);
                                
                                    if(is_null($store)){
                                        $userId = Auth::user()->id;
                                        $storeName = isset(Store::orderBy('id', 'DESC')->first()->id) ? ('store' . (Store::orderBy('id', 'DESC')->first()->id + 1)) : 'store1';
                                        Session::put('first_store', (object)['user_id' => $userId, 'store_name' => $storeName]);

                                        return redirect()->route('createFirstStore');
                                    }else{
                                        return redirect()->route('dashboard', ['subdomain' => $store->subdomain]);
                                    }
                                }else{
                                    Auth::logout();
                                    Session::flush();
                                    
                                    Session::flash('login_error', 'Your membership has expired. Please contact your admin.');
                                    return redirect()->route('login')->withInput(Input::except("password"));
                                }
                            }
                        }
                    }
                }else{
                    Session::flash('login_error', 'Invalid email or password!');
                    return redirect()->back()->withInput(Input::except("password"));
                }
            }

            return view('login', compact('loginMsg'));
        }else{
            return redirect()->route('listStore');
        }
    }

    public function register(Request $request){
        if($request->isMethod('POST')){
            $this->validate($request, [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'confirm_password' => 'required',
            ]);

            if($request->password == $request->confirm_password){
                $unique = UserDetail::where('first_name', $request->first_name)->where('last_name', $request->last_name)->count();
                if($unique == 0){
                    $input = Input::all();

                    $user = User::create([
                        'name' => $request->first_name.' '.$request->last_name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'role_id' => 1,
                        'active' => 1
                    ]);
    
                    $user_details = UserDetail::create([
                        'user_id' => $user->id,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                    ]);

                    Auth::login($user);
                    return redirect()->route('listStore');
                }else{
                    Session::flash('profile_error', 'First Name and Last Name already existing.');
                    return redirect()->back()->withInput(Input::all());
                }
            }else{
                Session::flash('profile_error', 'Password did not match');
                return redirect()->back()->withInput(Input::all());
            }
        }

        return view('register');
    }

    public function resetPassword(Request $request){
        if($request->isMethod('POST')){
            $this->validate($request, [
                'email' => 'required',
            ]);

            //insert reset function here
            $user = User::where('email', $request->email)->where('role_id', 3)->first();
            
            if(isset($user)){
                $tempPassword = $this->randomPassword($user);

                $data = [
                    'user' => $user,
                    'temp_password' => $tempPassword
                ];


                $site = Setup::where('key', 'site_name')->first();
                $email = EmailResponder::find(4);
                $from = $email->from;
                $to = $request->email;
                $subject = str_replace('%SITENAME%', $site->value , $email->subject);

                $body = $email->body;
                $body = str_replace('%FNAME%', $user->name , $body);
                $body = str_replace('%SITENAME%', $site->value , $body);
                $body = str_replace('%EMAIL%', $user->email , $body);
                $body = str_replace('%PASS%', $tempPassword , $body);
                $body = str_replace('%SITEURL%', route('updateProfile') , $body);

                Mail::send([], [], function ($message) use ($from, $to, $body, $subject, $site, $user) {
                    $message->from(env('MAIL_USERNAME'), $site->value);
                    $message->to($to, $user->name);
                    $message->subject($subject);
                    $message->setBody($body, 'text/html');
                });

                $user->update([
                    'password' => Hash::make($tempPassword)
                ]);

                // Mail::send('extra.email', $data, function ($m) use ($request, $data) {
                //     $m->from(env('MAIL_USERNAME'), "Instant Ecom Lab");
                //     $m->to($request->email, $data['user']->name)->subject('Password Reset');
                // });

                Session::flash('success_reset', 'Reset password request already sent.');
                return redirect()->route('login');
            }else{
                Session::flash('reset_error', 'Invalid email! email not existing.');
            }
        }

        return view('reset-password');
    }

    public function logout(Request $request){
        // $route = Auth::user()->role_id == 1 ? 'admin.login' : 'login';

        if(Auth::user()->role_id == 1) {
            $route = 'admin.login';
        }elseif(Auth::user()->role_id == 6) {
            $route = 'subadmin.login';
        }else{
            $route = 'login';
        }

        Auth::logout();
        Session::flush();
        $request->session()->invalidate();

        return redirect()->route($route);
    }

    public function randomPassword($user) {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $password = []; 
        $alphaLength = strlen($alphabet) - 1;
       
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $password[] = $alphabet[$n];
        }

        return implode($password);
    }

    public function getFirstStore($user){
        $store = Store::where('user_id', $user->id);

        if($store->count() > 0)
            return $store->first();
        else
            return null;
    }

    public function review(Request $request){
        if($request->isMethod('POST')){
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required',
                'ratings' => 'required',
                'review' => 'required', 
                'g-recaptcha-response' => 'required|captcha',
            ],
            [
                'email.required' => "The email field is required.",
                'password.required' => "The password field is required.",
                'ratings.required' => "The ratings field is required.",
                'review.required' => "The review field is required.",
                'g-recaptcha-response.required' => "Please verify that you are not a robot.",
                'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
            ]);

            //add status in reviews

            dd($request);
        }

        return view('register');
    }
}
