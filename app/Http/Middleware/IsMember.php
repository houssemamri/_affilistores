<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;
use Request;

class IsMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check()){
            $today = date('Y-m-d H:i:s');
            if(Auth::user()->role->id != 3){
                $this->forceLogout('Unauthorized access!');
                return redirect()->route('login');
            }elseif(Auth::user()->active == 0){
                $this->forceLogout('Account deactivated, Please contact your admin.');
                return redirect()->route('login');
            }elseif(isset(Auth::user()->memberDetail)){
                if(isset(Auth::user()->memberDetail->expiry_date) && $today > Auth::user()->memberDetail->expiry_date){
                    $this->forceLogout('Your membership has expired. Please contact your admin.');
                    return redirect()->route('login');
                }
            }
        }else{
            Session::flash('login_error', 'Your session seems to have expired. Please login again.');
            return redirect()->route('login');
        }

        return $next($request);
    }

    public function forceLogout($message){
        Auth::logout();
        Session::flush();
        Session::flash('login_error', $message);
    }
}
