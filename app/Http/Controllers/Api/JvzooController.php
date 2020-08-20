<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Response;
use App\JvzooProduct;

class JvzooController extends Controller
{
    public function search(Request $request){
        $items = JvzooProduct::where('product_name', 'LIKE', '%' . $request->keyword . '%')->orderBy('product_sales', 'DESC')->take(1000)->orderBy('id', 'DESC')->get();
        return Response::json($items);
    }
}
