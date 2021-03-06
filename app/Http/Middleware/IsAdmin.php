<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class IsAdmin
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
            if(Auth::user()->role->id > 1){
                Auth::logout();
                Session::flush();
                
                Session::flash('login_error', 'Unauthorized access!');
                return redirect()->route('login');
            }
        }else{
            Session::flash('login_error', 'Your session seems to have expired. Please login again.');
            return redirect()->route('admin.login');
        }
        

        return $next($request);
    }
}
