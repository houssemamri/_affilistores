<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use Input;
use Session;
use SimpleXMLElement;
use Route;
use Crypt;
use Purifier;
use Response;
use DateInterval;
use DatePeriod;
use DateTime;
use App\Store;
use App\Product;
use App\ProductHit;
use App\SocialCampaignLog;

class ReportsController extends GlobalController
{
    private $store;

    public function __construct(){
        parent::__construct();
        
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();
    }

    public function index(){
        $products = Product::where('store_id', $this->store->id)->get();
        $weeklyPosts = $this->weeklyPosts();
        $monthlyPosts = $this->monthlyPosts();
        $yearlyPosts = $this->yearlyPosts();

        $weeklyHits = $this->weeklyHits();
        $monthlyHits = $this->monthlyHits();
        $yearlyHits = $this->yearlyHits();
        
        $socialReports = $this->getSocialCampaignReport();

        return view('reports.index', compact('products', 'weeklyPosts', 'monthlyPosts', 'yearlyPosts', 'weeklyHits', 'monthlyHits', 'yearlyHits', 'socialReports'));
    }

    public function weeklyPosts(){
        $today = new DateTime(); 
        $today->add(new DateInterval('P1D'));
        
        $startDay = new DateTime(); 
        $startDay->sub(new DateInterval('P7D'));
        $pastTwentyDay = $startDay;

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($pastTwentyDay, $interval, $today);

        $data = [
            'days' => [], 
            'published' => [], 
            'drafts' => []
        ];

        foreach ($period as $value) {
            $data['days'][] = $value->format('F d');
            $data['published'][] = Product::where('store_id', $this->store->id)->where('status', 1)->whereDate('published_date', '=', $value->format('Y-m-d'))->count();
            $data['drafts'][] = Product::where('store_id', $this->store->id)->where('status', 0)->whereDate('published_date', '=', $value->format('Y-m-d'))->count();
        }

        return json_encode($data);
    }

    public function monthlyPosts(){
        $year = date('Y');
        $month = date('m');
        $endDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $data = [
            'days' => [], 
            'published' => [], 
            'drafts' => []
        ];

        for($i = 1; $i <= $endDay; $i++){
            $day = new DateTime($i . '-' . $month . '-' . $year);
            
            $data['days'][] = date_format($day, 'M d');
            $data['published'][] = Product::where('store_id', $this->store->id)->where('status', 1)->whereDate('published_date', '=', $day)->count();
            $data['drafts'][] = Product::where('store_id', $this->store->id)->where('status', 0)->whereDate('published_date', '=', $day)->count();
        }

        return json_encode($data);
    }

    public function yearlyPosts(){
        $data = [
            'days' => [], 
            'published' => [], 
            'drafts' => []
        ];

        for($m=1; $m<=12; ++$m){
            $month = date('F', mktime(0, 0, 0, $m, 1));
            $data['days'][] = $month;
            $data['published'][] = Product::where('store_id', $this->store->id)->where('status', 1)->whereMonth('published_date', '=', $m)->count();
            $data['drafts'][] = Product::where('store_id', $this->store->id)->where('status', 0)->whereMonth('published_date', '=', $m)->count();
        }

        return json_encode($data);
    }

    public function customPeriodPosts($subdomain, $startDate, $endDate){
        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);

        if($begin > $end)
            return Response::json(['succes' => false, 'msg' => 'Start date must be lower than End Date']);

        if($begin == $end){
            $data = [
                'days' => [], 
                'published' => [], 
                'drafts' => []
            ];

            $data['days'][] = $begin->format('m-d-Y');
            $data['published'][] = Product::where('store_id', $this->store->id)->where('status', 1)->whereDate('published_date', '=', $begin->format('Y-m-d'))->count();
            $data['drafts'][] = Product::where('store_id', $this->store->id)->where('status', 0)->whereDate('published_date', '=', $begin->format('Y-m-d'))->count();

        }else{
            $dateDifference = date_diff($begin, $end)->days;
            $skip = ($dateDifference > 30) ? intval($dateDifference / 15) : '1';
            $interval = DateInterval::createFromDateString($skip . ' day');
            
            $periods = new DatePeriod($begin, $interval, $end);

            $data = [
                'days' => [], 
                'published' => [], 
                'drafts' => []
            ];

            foreach($periods as $period) {
                $data['days'][] = $period->format('m-d-Y');
                $data['published'][] = Product::where('store_id', $this->store->id)->where('status', 1)->whereDate('published_date', '=', $period->format('Y-m-d'))->count();
                $data['drafts'][] = Product::where('store_id', $this->store->id)->where('status', 0)->whereDate('published_date', '=', $period->format('Y-m-d'))->count();
            }
        }
        

        return Response::json(['success' => true, 'custom' => json_encode($data)]);
    }

    public function weeklyHits(){
        $today = new DateTime(); 
        $today->add(new DateInterval('P1D'));
        
        $startDay = new DateTime(); 
        $startDay->sub(new DateInterval('P7D'));
        $pastSevenDay = $startDay;

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($pastSevenDay, $interval, $today);

        $data = [
            'days' => [], 
            'product_hits' => [], 
            'affiliate_hits' => []
        ];

        foreach ($period as $value) {
            $data['days'][] = $value->format('F d');
            $data['product_hits'][] = ProductHit::where('store_id', $this->store->id)->where('page_hits', 1)->whereDate('created_at', '=', $value->format('Y-m-d'))->count();
            $data['affiliate_hits'][] = ProductHit::where('store_id', $this->store->id)->where('affiliate_hits', 1)->whereDate('created_at', '=', $value->format('Y-m-d'))->count();
        }

        return json_encode($data);
    }

    public function monthlyHits(){
        $year = date('Y');
        $month = date('m');
        $endDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $data = [
            'days' => [], 
            'product_hits' => [], 
            'affiliate_hits' => []
        ];

        for($i = 1; $i <= $endDay; $i++){
            $day = new DateTime($i . '-' . $month . '-' . $year);
            
            $data['days'][] = date_format($day, 'M d');
           
            $data['product_hits'][] = ProductHit::where('store_id', $this->store->id)->where('page_hits', 1)->whereDate('created_at', '=', $day)->count();
            $data['affiliate_hits'][] = ProductHit::where('store_id', $this->store->id)->where('affiliate_hits', 1)->whereDate('created_at', '=',  $day)->count();
        }

        return json_encode($data);
    }

    public function yearlyHits(){
        $data = [
            'days' => [], 
            'product_hits' => [], 
            'affiliate_hits' => []
        ];

        for($m=1; $m<=12; ++$m){
            $month = date('F', mktime(0, 0, 0, $m, 1));
            $data['days'][] = $month;
            $data['product_hits'][] = ProductHit::where('store_id', $this->store->id)->where('page_hits', 1)->whereMonth('created_at', '=', $m)->count();
            $data['affiliate_hits'][] = ProductHit::where('store_id', $this->store->id)->where('affiliate_hits', 1)->whereMonth('created_at', '=',  $m)->count();
        }

        return json_encode($data);
    }

    public function customPeriodHits($subdomain, $startDate, $endDate){
        $begin = new DateTime($startDate);
        $end = new DateTime($endDate);
        $data = [
            'days' => [], 
            'product_hits' => [], 
            'affiliate_hits' => []
        ];

        if($begin > $end)
            return Response::json(['succes' => false, 'msg' => 'Start date must be lower than End Date']);

        if($begin == $end){
       
            $data['days'][] = $begin->format('m-d-Y');
            $data['product_hits'][] = ProductHit::where('store_id', $this->store->id)->where('page_hits', 1)->whereDate('created_at', '=', $begin->format('Y-m-d'))->count();
            $data['affiliate_hits'][] = ProductHit::where('store_id', $this->store->id)->where('affiliate_hits', 1)->whereDate('created_at', '=',  $begin->format('Y-m-d'))->count();
       
        }else{
            $dateDifference = date_diff($begin, $end)->days;

            if($dateDifference == 1){
                $periods = [$begin, $end];

                foreach($periods as $period) {
                    $data['days'][] = $period->format('m-d-Y');
                    $data['product_hits'][] = ProductHit::where('store_id', $this->store->id)->where('page_hits', 1)->whereDate('created_at', '=', $period->format('Y-m-d'))->count();
                    $data['affiliate_hits'][] = ProductHit::where('store_id', $this->store->id)->where('affiliate_hits', 1)->whereDate('created_at', '=', $period->format('Y-m-d'))->count();
                }
            }else{
                $skip = ($dateDifference > 30) ? intval($dateDifference / 15) : '1';
                $interval = DateInterval::createFromDateString($skip . ' day');
                $periods = new DatePeriod($begin, $interval, $end);
    
                foreach($periods as $period) {
                    $data['days'][] = $period->format('m-d-Y');
                    $data['product_hits'][] = ProductHit::where('store_id', $this->store->id)->where('page_hits', 1)->whereDate('created_at', '=', $period->format('Y-m-d'))->count();
                    $data['affiliate_hits'][] = ProductHit::where('store_id', $this->store->id)->where('affiliate_hits', 1)->whereDate('created_at', '=', $period->format('Y-m-d'))->count();
                }
            }
        }
        

        return Response::json(['success' => true, 'custom' => json_encode($data)]);
    }

    public function productPageHit(Request $request){
        $productId = Crypt::decrypt($request->productId);
        $storeId = Crypt::decrypt($request->storeId);
        $productHit = ProductHit::where('store_id', $storeId)->where('product_id', $productId)->first();
        
        // if(isset($productHit)){
        //     $productHit->update([
        //         'page_hits' => $productHit->page_hits + 1
        //     ]);
        // }else{
            ProductHit::create([
                'store_id' => $storeId,
                'product_id' => $productId,
                'page_hits' => 1
            ]);
        // }
        
        return Response::json(['success' => true]);
    }

    public function productAffiliateHit(Request $request){
        $productId = Crypt::decrypt($request->productId);
        $storeId = Crypt::decrypt($request->storeId);
        $productHit = ProductHit::where('store_id', $storeId)->where('product_id', $productId)->first();
        
        // if(isset($productHit)){
        //     $productHit->update([
        //         'affiliate_hits' => $productHit->affiliate_hits + 1
        //     ]);
        // }else{
            ProductHit::create([
                'store_id' => $storeId,
                'product_id' => $productId,
                'affiliate_hits' => 1
            ]);
        // }
        

        return Response::json(['success' => $productHit->affiliate_hits]);
    }

    public function getSocialCampaignReport(){
        $socialReports = SocialCampaignLog::where('store_id', $this->store->id)->get();

        return $socialReports;
    }
}
