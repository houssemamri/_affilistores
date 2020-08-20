<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\ClickBankProduct;

class ClickBankController extends Controller
{
    public function search(Request $request){
        $items = ClickBankProduct::orWhere('title', 'LIKE', '%' . $request->keyword . '%')
                    ->orWhere('description', 'LIKE', '%' . $request->keyword . '%')
                    ->orderBy('percent_per_sale', 'DESC')
                    ->take(1000)->get();
        return Response::json($items);
    }
}
