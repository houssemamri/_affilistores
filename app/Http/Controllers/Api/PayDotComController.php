<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\PayDotComProduct;

class PayDotComController extends Controller
{
    public function search(Request $request){
        $items = PayDotComProduct::orWhere('name', 'LIKE', '%' . $request->keyword . '%')
                    ->orWhere('description', 'LIKE', '%' . $request->keyword . '%')->take(1000)->get();
                    
        return Response::json($items);
    }
}
