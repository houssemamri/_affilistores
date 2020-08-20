<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class IsStoreSelected
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

        $store = Store::where('subdomain', $subdomain)->get();
        
        if(count($store) == 0){
            Session::flash('error', 'Please select a store to manage on');
            return redirect()->route('listStore');
        }

        return $next($request);
    }
}
