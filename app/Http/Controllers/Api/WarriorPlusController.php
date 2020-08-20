<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\WarriorPlusProduct;

class WarriorPlusController extends Controller
{
    public function search(Request $request){
        $items = WarriorPlusProduct::orWhere('offer_name', 'LIKE', '%' . $request->keyword . '%')->orderBy('sales_range', 'DESC')->take(1000)->get();

        return Response::json($items);
    }
}
