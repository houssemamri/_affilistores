<?php

namespace App\Http\Middleware;

use Closure;
use Crypt;
use Session;
use App\Store;

class IsSubDomain
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
        $url = parse_url($request->url());
        $domain = explode('.', $url['host']);
        $subdomain = $domain[0];

        $store = Store::where('subdomain', $subdomain)->first();

        if(!isset($store)){
            return redirect()->route('main.index');
        }elseif(isset($store) && $store->status == 0){
            return redirect()->route('main.index.store.close', Crypt::encrypt($store->id));
        }
        Session::put('subdomain', $store->subdomain); 

        return $next($request);
    }
}
