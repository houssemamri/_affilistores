<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use Session;
use Route;
use Crypt;
use App\Store;
use App\SocialProof;
use App\ProductHit;
use App\Product;
use App\StoreSocialProofSetting;

class SocialProofController extends GlobalController
{
    private $store;

    public function __construct(){
        parent::__construct();
        
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();
    }


    public function index(){
        $socialProofs = $this->store->socialProofs;
        $store = $this->store;

        return view('increase-conversion.social-proofs.index', compact('socialProofs', 'store'));
    }

    public function create(Request $request){
        if($request->isMethod('POST')){
            $this->validate($request, [
                'image' => 'required',
                'title' => 'required',
                'content' => 'required',
                'link' => 'required',
                'display_time' => 'required',
                'time_difference' => 'required',
            ]);
            
            $image = (isset($request->image)) ? $this->uploadImg($request) : '';

            $data = [
                'image' => $image,
                'title' => $request->title,
                'content' => $request->content,
                'url' => $request->link,
                'settings' => [
                    'display_time' => $request->display_time,
                    'time_difference' => $request->time_difference,
                ],
            ];

            SocialProof::create([
                'store_id' => $this->store->id,
                'content' => json_encode($data),
                'type' => 'custom',
                'active' => isset($request->display) ? 1 : 0
            ]);

            Session::flash('success', 'Social Proof successfully created');
            return redirect()->route('socialProof.index', $this->store->subdomain);
        }

        return view('increase-conversion.social-proofs.create');
    }

    public function edit(Request $request, $subdomain, $id){
        $proof = SocialProof::find($id);
        $proofData = json_decode($proof->content);

        if($request->isMethod('POST')){
            $this->validate($request, [
                'title' => 'required',
                'content' => 'required',
                'link' => 'required',
                'display_time' => 'required',
                'time_difference' => 'required',
            ]);
            
            $image = (isset($request->image)) ? $this->uploadImg($request) : $proofData->image;

            $data = [
                'image' => $image,
                'title' => $request->title,
                'content' => $request->content,
                'url' => $request->link,
                'settings' => [
                    'display_time' => $request->display_time,
                    'time_difference' => $request->time_difference,
                ],
            ];

            $proof->update([
                'content' => json_encode($data),
                'active' => isset($request->display) ? 1 : 0
            ]);

            Session::flash('success', 'Social Proof successfully udpated');
            return redirect()->route('socialProof.index', $this->store->subdomain);
        }

        return view('increase-conversion.social-proofs.edit', compact('id', 'proof', 'proofData'));
    }

    public function delete(Request $request){
        $proof = SocialProof::find(Crypt::decrypt($request->proof_id))->delete();

        Session::flash('success', 'Social Proof successfully deleted');
        return redirect()->back();
    }

    public function uploadImg(Request $request){
        $file = $request->file('image');
        $fileExtension = $file->getClientOriginalExtension();
        $fileName = 'SP_IMG_' . time() . '.' . $fileExtension;

        $file->move('img/uploads/' . $this->store->subdomain . '/social-proof', $fileName);

        return asset('img/uploads/' . $this->store->subdomain . '/social-proof/' . $fileName);
    }

    public function orderSocialProof(Request $request, $subdomain){
        for ($i=0; $i < count($request->orderProofs); $i++) { 
            $id = $request->orderProofs[$i];

            $proof = SocialProof::find($id)->update([
                'order' => $i + 1
            ]);
        }

        Session::flash('success', 'Social Proof ordering successfully saved');
        return redirect()->back();
    }

    public function randomOrder($subdomain, $type){
        $settings = [
            'display_order' => $type
        ];

        $this->store->socialProofSetting->update([
            'settings' => json_encode($settings)
        ]);

        Session::flash('success', 'Social Proof set to ' . $type . ' display');
        return redirect()->back();
    }

    public function display(Request $request, $subdomain, $id){
        $decrypted = Crypt::decrypt($id);
        $proof = SocialProof::find($decrypted);

        $proof->update([
            'active' => 1 
        ]);

        Session::flash('success', 'Social Proof successfully display');
        return redirect()->back();
    }

    public function hide(Request $request, $subdomain, $id){
        $decrypted = Crypt::decrypt($id);
        $proof = SocialProof::find($decrypted);

        $proof->update([
            'active' => 0
        ]);

        Session::flash('success', 'Social Proof successfully hide');
        return redirect()->back();
    }

    public function getNewSocialProofs(Request $request){
        foreach ($this->getProductsWithHits() as $hits) {
            $data = [
                'image' => $hits->product->image,
                'title' => $hits->product->name,
                'content' => $hits->page_hits == 1 ? 'New product click' : 'Reached ' . $hits->page_hits . ' product clicks',
                'url' => route('index.product.show', ['subdomain' => $this->store->subdomain, 'permalink' => $hits->product->permalink]),
                'settings' => [
                    'display_time' => '10',
                    'time_difference' => '10',
                ],
            ];

            SocialProof::create([
                'store_id' => $this->store->id,
                'content' => json_encode($data),
                'type' => 'custom',
                'active' => 0
            ]);
        }

        foreach ($this->getProductsWithAffiliateHits() as $hits) {
            $data = [
                'image' => $hits->product->image,
                'title' => $hits->product->name,
                'content' => $hits->affiliate_hits == 1 ? 'New product affliate click' : 'Reached ' . $hits->affiliate_hits . ' affliate clicks',
                'url' => route('index.product.show', ['subdomain' => $this->store->subdomain, 'permalink' => $hits->product->permalink]),
                'settings' => [
                    'display_time' => '10',
                    'time_difference' => '10',
                ],
            ];

            SocialProof::create([
                'store_id' => $this->store->id,
                'content' => json_encode($data),
                'type' => 'custom',
                'active' => 0
            ]);
        }

        Session::flash('success', 'Social proofs successfully updated');
        return redirect()->back();
    }

    public function getProductsWithHits(){
        $products = [];
        $productHits = ProductHit::where('store_id', $this->store->id)->get();

        foreach ($productHits as $productHit) {
            if($productHit->page_hits > 0)
                array_push($products, $productHit);
        }

        return $products;
    }

    public function getProductsWithAffiliateHits(){
        $products = [];
        $productHits = ProductHit::where('store_id', $this->store->id)->get();

        foreach ($productHits as $productHit) {
            if($productHit->affiliate_hits > 0)
                array_push($products, $productHit);
        }

        return $products;
    }

    
    public function displayHideSocialProofs(Request $request){
        $socialProofIds = explode(',', $request->socialProofsId);
        $active = $request->status == 'display' ? 1 : 0;

        foreach ($socialProofIds as $socialProofId) {
            if($socialProofId !== ''){
                $proof = SocialProof::find($socialProofId);
                if(isset($proof)){
                   $proof->update([
                       'active' => $active
                   ]);
                }
            }
        }

        Session::flash('success', $active == 1 ? 'Social proofs successfully display' : 'Social proofs successfully hide');
        return redirect()->back();
    }

    public function deleteMultiple(Request $request){
        $socialProofIds = explode(',', $request->deleteProducts);

        foreach ($socialProofIds as $socialProofId) {
            if($socialProofId !== ''){
                $socialProof = SocialProof::where('store_id', $this->store->id)->where('id', $socialProofId)->first();

                if(isset($socialProof)){
                    $socialProof->delete();
                }
            }
        }

        Session::flash('success', 'Social Proof successfully deleted');
        return redirect()->back();
    }
}
