<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\GlobalController;
use Session;
use DateInterval;
use DatePeriod;
use DateTime;
use App\Audit;
use App\Store;
use App\Poll;
use App\MemberDetail;

class DashboardController extends GlobalController
{
    public function index(){
        $today = new DateTime(); 
        $today->add(new DateInterval('P1D'));
        
        $startDay = new DateTime(); 
        $startDay->sub(new DateInterval('P20D'));
        $pastTwentyDay = $startDay;

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($pastTwentyDay, $interval, $today);

        $data = [
            'days' => [], 
            'login' => [], 
            'sign_up' => []
        ];

        foreach ($period as $value) {
            $data['days'][] = $value->format('F d');
            $data['login'][] = Audit::where('type', 'login')->whereDate('created_at', '=', $value->format('Y-m-d'))->count();
            $data['sign_up'][] = Audit::where('type', 'signup')->whereDate('created_at', '=', $value->format('Y-m-d'))->count();
        }

        $data = json_encode($data);

        $stores = Store::count();
        $poll = Poll::first();
        $members = MemberDetail::count();


        return view('admin.index', compact('data', 'stores', 'poll', 'members'));
    }
}
