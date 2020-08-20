<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use Session;
use DateTime;
use Crypt;
use Route;
use App\Store;
use App\Tag;
use App\ProductTag;

class TagController extends GlobalController
{
    private $store;

    public function __construct(){
        parent::__construct();
        
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();
    }

    public function index(){
        $tags = Tag::where('store_id', $this->store->id)->get();

        return view('products.tags.index', compact('tags'));
    }

    public function create(Request $request, $subdomain){
        if($request->isMethod('POST')){
            $this->validate($request, [
                'name' => 'required|unique:tags,name',
            ]);

            Tag::create([
                'store_id' => $this->store->id,
                'name' => strtolower($request->name),
                'status' => isset($request->status) ? 1 : 0
            ]);
            
            Session::flash('success', 'Tag ' . $request->name . ' created successfully');
            return redirect()->route('tags.index', $this->store->subdomain);
        }

        return view('products.tags.create');
    }

    public function edit(Request $request, $subdomain, $id){
        $decrypted = Crypt::decrypt($id);
        $tag = Tag::find($decrypted);

        if($request->isMethod('POST')){
            $this->validate($request, [
                'name' => 'required|unique:tags,name,' . $tag->id,
            ]);

            $tag->update([
                'store_id' => $this->store->id,
                'name' => strtolower($request->name),
                'status' => isset($request->status) ? 1 : 0
            ]);
            
            Session::flash('success', 'Tag ' . $request->name . ' updated successfully');
            return redirect()->route('tags.index', $this->store->subdomain);
        }

        return view('products.tags.edit', compact('tag', 'id'));
    }

    public function delete(Request $request, $subdomain){
        $decrypted = Crypt::decrypt($request->tag_id);
        $tag = Tag::find($decrypted);

        $productTags = ProductTag::where('tag_id', $tag->id)->delete();

        //insert here delete product tags 
        $tag->delete();
        
        Session::flash('success', 'Tag ' . $tag->name . ' deleted successfully');
        return redirect()->route('tags.index', $this->store->subdomain);
    }

}
