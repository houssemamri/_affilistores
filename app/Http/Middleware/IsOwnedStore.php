<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;
use Request;

class IsOwnedStore
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
        if($this->checkOwnedStore($request)){
            $this->forceLogout('Unauthorized access of store!');
            return redirect()->route('login');
        }
        return $next($request);
    }

    public function checkOwnedStore($request){
        $url = parse_url($request->url());
        $domain = explode('.', $url['host']);
        $subdomain = $domain[0];

        $store = Auth::user()->stores->where('subdomain', $subdomain)->first();

        if(isset($store)){
            return false;
        }

        return true;
    }

    public function forceLogout($message){
        Auth::logout();
        Session::flush();
        Session::flash('login_error', $message);
    }
}
