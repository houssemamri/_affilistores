<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GlobalController;
use Carbon\Carbon;
use Session;
use Crypt;
use Route;
use App\Store;
use App\Category;
use App\Tag;
use App\Automation;
use App\AccessFeature;

class AutomationController extends GlobalController
{
    private $store;
    private $features;

    public function __construct(){
        parent::__construct();
        
        $subdomain = Route::current()->parameter('subdomain');
        $this->store = Store::where('subdomain', $subdomain)->first();
        $this->features = $this->getFeatures();
    }

    public function index(){
        $automations = Automation::where('store_id', $this->store->id)->get();

        return view('automation.index', compact('automations'));
    }

    public function create(Request $request){
        $categories = Category::where('store_id', $this->store->id)->where('status', 1)->get();
        $stores = $this->getAffiliatesSettings();
        $features = $this->getFeatures();
        $tags = implode(',', Tag::where('store_id', $this->store->id)->pluck('name')->toArray());
        $searchIndices = json_encode($this->getSearchIndices());
        $product_data = [];

        if($request->isMethod('POST')){
            $this->validate($request, [
                'source' => 'required',
                'category' => 'required',
                'keyword' => 'required',
                'number_of_daily_post' => 'required',
                'start_date' => 'required',
                'end_date' => 'required'
            ]);

            $tagsArray = isset($request->tags) ? explode(',', $request->tags) : [];  
            if(!isset($request->categories)){
                Session::flash('error', 'Please select atleast one product category');
                return redirect()->back()->withInput($request->input());
            }

            if(count($tagsArray) == 0){
                Session::flash('error', 'Please select atleast one product tag');
                return redirect()->back()->withInput($request->input());
            }

            if(!(isset($request->publish) && $request->publish == 'now')){
                $this->validate($request, [
                    'published_date' => 'required',
                ]);
            }

            $categories = implode(',', $request->categories);
            $productTags = implode(',', $this->getProductTags($tagsArray));
            
            $product_data = [
                'categories' => $categories,
                'tags' => $productTags,
                'published_date' => isset($request->publish) && $request->publish == 'now' ? date('Y-m-d H:i:s') : date_format(date_create($request->published_date), 'Y-m-d H:i:s'),
                'status' => isset($request->publish) && $request->publish == 'now' ? 1 : 0,
            ];

            $automation = Automation::create([
                'store_id' => $this->store->id,
                'source' => $request->source,
                'category' => $request->category,
                'keyword' => $request->keyword,
                'number_daily_post' => $request->number_of_daily_post,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'product_data' => json_encode($product_data)
            ]);

            Session::flash('success', 'Automation successfully added');
            return redirect()->route('automation.index', $this->store->subdomain);
        }

        return view('automation.create', compact('categories', 'tags', 'searchIndices', 'stores', 'features'));
    }

    public function getFeatures(){
        $features = [];
        $accessFeatures = AccessFeature::where('membership_id', $this->store->user->memberDetail->membership->id)->get();

        foreach ($accessFeatures as $feature) {
            array_push($features, strtolower(str_replace('.', '', $feature->features->name)));
        }

        return $features;
    }

    public function getProductTags($tags){
        $productTags = [];

        foreach ($tags as $tag) {
            //create tag
            $tagExist = Tag::where('name', 'like', $tag)->first();

            if(!isset($tagExist)){
                $tag = Tag::create([
                    'store_id' => $this->store->id,
                    'name' => strtolower($tag),
                    'status' => 1
                ]);
                array_push($productTags, $tag->id);
                
            }else{
                array_push($productTags, $tagExist->id);
            }
        }

        return $productTags;
    }

    public function edit(Request $request, $subdomain, $id){
        $decrypted = Crypt::decrypt($id);
        $automation = Automation::find($decrypted);
        $categories = Category::where('store_id', $this->store->id)->where('status', 1)->get();
        $tags = Tag::where('store_id', $this->store->id)->whereIn('id', explode(',', json_decode($automation->product_data)->tags))->pluck('name')->toArray();
        $stores = $this->getAffiliatesSettings();
        $features = $this->getFeatures();
        $searchIndices = json_encode($this->getSearchIndices());
        $searchIndex = $this->getSearchIndices()[$automation->source];
        $product_data = [];
        
        if($request->isMethod('POST')){
            $this->validate($request, [
                'source' => 'required',
                'category' => 'required',
                'keyword' => 'required',
                'number_of_daily_post' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'published_date' => 'required'
            ]);

            $tagsArray = isset($request->tags) ? explode(',', $request->tags) : [];  

            if(!isset($request->categories)){
                Session::flash('error', 'Please select atleast one product category');
                return redirect()->back()->withInput($request->input());
            }

            if(count($tagsArray) == 0){
                Session::flash('error', 'Please select atleast one product tag');
                return redirect()->back()->withInput($request->input());
            }

            $categories = implode(',', $request->categories);
            $productTags = implode(',', $this->getProductTags($tagsArray));
            
            $product_data = [
                'categories' => $categories,
                'tags' => $productTags,
                'published_date' => $request->published_date,
                'status' => json_decode($automation->product_data)->status,
            ];

            $automation->update([
                'source' => $request->source,
                'category' => $request->category,
                'keyword' => $request->keyword,
                'number_daily_post' => $request->number_of_daily_post,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'product_data' => json_encode($product_data)
            ]);

            Session::flash('success', 'Automation successfully added');
            return redirect()->route('automation.index', $this->store->subdomain);
        }

        return view('automation.edit', compact('id', 'automation', 'tags', 'categories', 'features', 'searchIndex', 'searchIndices', 'stores'));
    }

    public function delete(Request $request){
        $decrypted = Crypt::decrypt($request->automation_id);
        $automation = Automation::find($decrypted)->delete();
        
        Session::flash('success', 'Automation successfully deleted');
        return redirect()->back();
    }

    public function getAffiliatesSettings(){
        $stores = [];

        foreach ($this->store->affiliateSettings as $affiliate) {
            $settings = json_decode($affiliate->settings);

            foreach ($settings as $key => $setting) {
                if($affiliate->name == 'ebay'){
                    if($key == 'application_id' && $setting == ""){
                        $stores[$affiliate->name] = false;
                        break;
                    }
                }else{
                    if($setting == ""){
                        $stores[$affiliate->name] = false;
                        break;
                    }
                }
            }

            $stores[$affiliate->name] = isset($stores[$affiliate->name]) && $stores[$affiliate->name] == false ? $stores[$affiliate->name] : true;
        }

        return $stores;
    }

    public function getSearchIndices(){
        $amazon = [
            'All',
            'Apparel',
            'Automotive',
            'Baby',
            'Beauty',
            'Books',
            'DVD',
            'Electronics',
            'GiftCards',
            'Grocery',
            'HealthPersonalCare',
            'HomeGarden',
            'Jewelry',
            'KindleStore',
            'Luggage',
            'Music',
            'MusicalInstruments',
            'OfficeProducts',
            'PCHardware',
            'PetSupplies',
            'Shoes',
            'Software',
            'SportingGoods',
            'Toys',
            'VideoGames',
            'Watches',
            'Wireless'
        ];

        $ebay = [
            'All' => '1',
            'Art' => '550',
            'Baby' => '2984',
            'Books, Comics &amp; Magazines' => '267',
            'Business, Office &amp; Industrial' => '12576',
            'Cameras & Photography' => '625',
            'Cars, Motorcycles &amp; Vehicles' => '6001',
            'Clothes, Shoes &amp; Accessories' => '11450',
            'Coins' => '11116',
            'Computers/Tablets &amp; Networking' => '58058',
            'Consumer Electronics' => '293',
            'Crafts' => '14339',
            'Dolls &amp; Bears' => '237',
            'DVDs, Films &amp; TV' => '11232',
            'Events Tickets' => '1305',
            'Mobile Phones &amp; Communication' => '15032',
            'Health &amp; Beauty' => '26395',
            'Garden &amp; Patio' => '159912',
            'Jewelry &amp; Watches' => '281',
            'Music' => '11233',
            'Musical Instruments' => '619',
            'Pet Supplies' => '1281',
            'Pottery, Porcelain &amp; Glass' => '870',
            'Property' => '10542',
            'Sporting Goods' => '888',
            'Sound &amp; Vision' => '293',
            'Stamps' => '260',
            'Sports Memorabilia' => '64482',
            'Toys &amp; Hobbies' => '220',
            'Video Games &amp; Consoles' => '1249',
            'Decals, Stickers &amp; Vinyl Art' => '159889',
        ];

        $aliexpress = [
            "All" => '',
            "Automobiles & Motorcycles" => "34",
            "Beauty & Health" => "66",
            "Cellphones & Telecommunications" => "509",
            "Computer & Office" => "7",
            "Consumer Electronics" => "44",
            "Electronic Components & Supplies" => "502",
            "Furniture" => "1503",
            "Hair Extensions & Wigs" => "200002489",
            "Hair & Accessories" => "200165144",
            "Home Appliances" => "6",
            "Home Improvement" => "13",
            "Home & Garden" => "15",
            "Jewelry & Accessories" => "1509",
            "Lights & Lighting" => "39",
            "Luggage & Bags" => "1524",
            "Men&apos;s Clothing & Accessories" => "100003070",
            "Mother & Kids" => "1501",
            "Novelty & Special Use" => "200000875",
            "Office & School Supplies" => "21",
            "Security & Protection" => "30",
            "Shoes" => "322",
            "Sports & Entertainment" => "18",
            "Tools" => "1420",
            "Toys & Hobbies" => "26",
            "Watches" => "1511",
            "Weddings & Events" => "100003235",
            "Women&apos;s Clothing & Accessories" => "100003109",
        ];

        $walmart = [
            'All' => '',
            "Arts, Crafts & Sewing" => "1334134",
            "Auto & Tires" => "91083",
            "Baby" => "5427",
            "Beauty" => "1085666",
            "Books" => "3920",
            "Cell Phones" => "1105910",
            "Clothing" => "5438",
            "Electronics" => "3944",
            "Food" => "976759",
            "Gifts & Registry" => "1094765",
            "Health" => "976760",
            "Home" => "4044",
            "Home Improvement" => "1072864",
            "Household Essentials" => "1115193",
            "Industrial & Scientific" => "6197502",
            "Jewelry" => "3891",
            "Movies & TV Shows" => "4096",
            "Music on CD or Vinyl" => "4104",
            "Musical Instruments" => "7796869",
            "Office" => "1229749",
            "Party & Occasions" => "2637",
            "Patio & Garden" => "5428",
            "Personal Care" => "1005862",
            "Pets" => "5440",
            "Photo Center" => "5426",
            "Premium Beauty" => "7924299",
            "Seasonal" => "1085632",
            "Sports & Outdoors" => "4125",
            "Toys" => "4171",
            "Video Games" => "2636",
            "Walmart for Business" => "6735581",
        ];

        $shopcom = [
            'All' => '',
            "Baby" => "1-32804",
            "Baby" => "1-32811",
            "Books" => "1-32836",
            "Beauty" => "1-32867",
            "Business" => "1-32820",
            "Cameras" => "1-32837",
            "Clothes" => "1-32838",
            "Computers" => "1-32862",
            "Collectibles" => "1-32839",
            "Crafts" => "1-32835",
            "Electronics" => "1-32863",
            "Food and Drink" => "1-32806",
            "Garden" => "1-32808",
            "Health & Nutrition" => "1-32841",
            "Home Store" => "1-32842",
            "Jewelry" => "1-32800",
            "Movies" => "1-32819",
            "Music" => "1-32812",
            "Party Supplies" => "1-32840",
            "Pet Supplies" => "1-32809",
            "Posters" => "1-32807",
            "Shoes" => "1-32805",
            "Software" => "1-32864",
            "Sports and Fitness" => "1-32844",
            "Sports Fan Shop" => "1-32877",
            "Travel" => "1-32813",
            "Tools" => "1-32843",
            "Toys" => "1-32810",
            "Video Games" => "1-32866",
        ];

        $cjcom = [
            'All' => 'any'
        ];

        $jvzoo = [
            'All' => 'any'
        ];

        $clickbank = [
            'All' => 'any'
        ];
        
        $warriorplus = [
            'All' => 'any'
        ];

        $paydotcom = [
            'All' => 'any'
        ];

        $source = [
            'amazon' => $amazon,
            'ebay' => $ebay,
            'aliexpress' => $aliexpress,
            'walmart' => $walmart,
            'shopcom' => $shopcom,
            'cjcom' => $cjcom,
            'jvzoo' => $jvzoo,
            'clickbank' => $clickbank,
            'warriorplus' => $warriorplus,
            'paydotcom' => $paydotcom
        ];

        return $source;
    }
}
