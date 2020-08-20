<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Request;
use Session;

class HasAccess
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
        $accessCtr = 0;
        if(Auth::user()){
            foreach(Auth::user()->memberDetail->membership->accessRights as $access){
                if($access->menu->slug == Request::segment(2))
                    $accessCtr++;
            }
    
            if($accessCtr == 0){
                $this->forceLogout('Unauthorize access. Please login again. Thank you');
                return redirect()->route('login');
            }
        }
        
        return $next($request);
    }

    public function forceLogout($message){
        Auth::logout();
        Session::flush();
        Session::flash('login_error', $message);
    }
}
