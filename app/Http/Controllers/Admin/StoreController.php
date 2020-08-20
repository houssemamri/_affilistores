<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\GlobalController;
use App\Http\Controllers\Controller;
use Crypt;
use Session;
use App\Store;
use App\User;
use App\Product;

class StoreController extends GlobalController
{
    public function index(){
        $stores = Store::withCount([
            'products', 'pageHits', 'affiliateHits',
        ])->with('user')->get();
        // $stores = Store::with('user:id,name')->get();
        return view('admin.store.index', compact('stores'));
    }

    public function getStores(Request $request){
        $columns = [
            "name",
            "subdomain",
            "owner",
            "products_count",
            "created_at",
            "page_hits_count",
            "affiliate_hits_count",
            "status",
            "action",
        ];

        $totalData = Store::count();
        $limit = $request->length;
        $start = $request->start;
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
      
        // $stores = Store::withCount([ 'products', 'pageHits', 'affiliateHits', ])
        $stores = Store::withCount([ 'pageHits', 'affiliateHits' ])
                ->with('user:id,name')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

        
        return  json_encode([
            "draw" => intval($request->input('draw')),
            "recordsTotal"	=> intval($totalData),
            "recordsFiltered" => intval($totalData),
            "data" => $this->getData($stores)
        ]);
    }

    public function getData($stores){
        $data = [];

        if($stores){
            foreach ($stores as $store) {
                array_push($data, [
                    "name" => $store->name,
                    "subdomain" => $store->subdomain,
                    "owner" => $store->user->name,
                    "products_count" => $this->getNumberProducts($store->id),
                    "created_at" => date('F d, Y h:i a', strtotime($store->created_at)),
                    "page_hits_count" => $store->page_hits_count,
                    "affiliate_hits_count" => $store->affiliate_hits_count,
                    "status" => $store->status == 1 ? '<span class="badge badge-success">Open</span>' : '<span class="badge badge-danger">Close</span>',
                    "action" => $this->getAction($store),
                ]);
            }
        }

        return $data;
    }

    public function getNumberProducts($storeId){
        return Product::where('store_id', $storeId)->count();
    }

    public function getAction($store){
        $action = '';

        $action .= '<div class="btn-group" role="group" aria-label="Basic example">';
            if($store->status == 1) 
                $action .= '    <a class="btn btn-sm btn-default" href="' . route('admin.store.change.status', [Crypt::encrypt($store->id), 'close']) . '">Close</a>';
            elseif($store->status == 0) 
                $action .= '    <a class="btn btn-sm btn-default" href="' . route('admin.store.change.status', [Crypt::encrypt($store->id), 'open']) . '">Open</a>';
            if($store->featured == 1)
                $action .= '    <a class="btn btn-sm btn-default" href="' . route('admin.store.change.status', [Crypt::encrypt($store->id), 'unfeatured']) . '">Unfeature</a>';
            elseif($store->featured == 0)
                $action .= '    <a class="btn btn-sm btn-default" href="' . route('admin.store.change.status', [Crypt::encrypt($store->id), 'featured']) . '">Feature</a>';
        $action .= '</div>';

        return $action;
    }

    public function changeStatus(Request $request, $id, $status){
        $store = Store::where('id', Crypt::decrypt($id))->first();

        if(isset($store)){
            if($status == 'close'){
                $store->update([ 'status' => 0 ]);
            }elseif($status == 'open'){
                $store->update([ 'status' => 1 ]);
            }elseif($status == 'unfeatured'){
                $store->update([ 'featured' => 0 ]);
            }elseif($status == 'featured'){
                $store->update([ 'featured' => 1 ]);
            }

            Session::flash('success', 'Store ' . $store->name . ' successfully ' . $status);
        }else{
            Session::flash('error', 'Oops something went wrong! Invalid store id.');
        }

        return redirect()->route('admin.store.index');
    }
}
