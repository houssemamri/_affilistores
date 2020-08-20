<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use Session;
use Route;
use App\Category;
use App\Product;
use App\Store;
use App\ProductCategory;

class PingerController extends GlobalController
{
    private $store;

    public function __construct(){
        parent::__construct();
        
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();
    }

    public function index(Request $request){
        $products = $this->store->products;
        $categories = $this->store->categories;

        if($request->isMethod('POST')){
            $product = Product::find($request->productToPing);

            if(isset($product)){
                $url = route('index.product.show', ['subdomain' => $this->store->subdomain, 'permalink' => $product->permalink]);
                $ping = $this->pingPingOMatic($product->name, $url);

                if(strpos($ping, 'Slow down cowboy!') !== false)
                    Session::flash('error', 'Slow it down, your last ping was less than a few minutes ago. Please only ping when you update your product page');
                elseif(strpos($ping, 'Pinging complete!') !== false)
                    Session::flash('success', 'Ping successfully sent!');
            }

            return redirect()->back();
        }

        return view('pinger.index', compact('products', 'categories'));   
    }

    public function pingPingOMatic($title, $url)
    {
        $entity = [
            'chk_weblogscom'    => 'on',
            'chk_blogs'         => 'on',
            'chk_feedburner'    => 'on',
            'chk_newsgator'     => 'on',
            'chk_myyahoo'       => 'on',
            'chk_pubsubcom'     => 'on',
            'chk_blogdigger'    => 'on',
            'chk_weblogalot'    => 'on',
            'chk_newsisfree'    => 'on',
            'chk_topicexchange' => 'on',
            'chk_google'        => 'on',
            'chk_tailrank'      => 'on',
            'chk_skygrid'       => 'on',
            'chk_collecta'      => 'on',
            'chk_superfeedr'    => 'on',
        ];

        $entity['title'] = urlencode($title);
        $entity['blogurl'] = urlencode($url);

        $query_string = http_build_query($entity);
        $service_url = 'http://pingomatic.com/ping/?'.$query_string;
        return $this->sendPing($service_url, $query_string);
    }

    private function sendPing($url, $post)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    public function getProducts($subdomain, $category){
        $productCategory = ProductCategory::where('category_id', $category)->get();
        $products = [];

        foreach ($productCategory as $product) {
            array_push($products, [
                'id' => $product->product->id,
                'name' => $product->product->name
            ]);
        }
        
        return $products;
    }
}
