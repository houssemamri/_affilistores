<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\GlobalController;
use SimpleXMLElement;
use Session;
use File;
use App\ClickBankProduct;
use App\JvzooProduct;
use App\WarriorPlusProduct;
use App\PayDotComProduct;
use App\ImportLog;

class ImportController extends GlobalController
{
    public function index(Request $request){
        $logs = ImportLog::orderBy('id', 'DESC')->get();

        if($request->isMethod('POST')){
            $type = '';

            if($request->ecommerce == 'jvzoo'){
                $type = 'jvzoo';
                $file = $request->file('product_file');
                $products = $this->jvzooProducts($file);
                // JvzooProduct::truncate();

                foreach ($products['new_products'] as $newProduct) {
                    $insert = $this->insertJvzooProducts($products['products'][$newProduct]);
                }
            }elseif($request->ecommerce == 'click_bank'){
                $type = 'click_bank';
                $clickbank = json_decode($this->parseXml($request), TRUE);
                $products = $this->clickBankProducts($clickbank);

                // ClickBankProduct::truncate();

                foreach ($products as $product) {
                    $product = (object) $product;
                    $this->insertClickBankProducts($product);
                }
            }elseif($request->ecommerce == 'warrior_plus'){
                $type = 'warrior_plus';
                $file = $request->file('product_file');
                $products = $this->warriorPlusProducts($file);

                // WarriorPlusProduct::truncate();

                foreach ($products as $product) {
                    $product = (object) $product;
                    $this->insertWarriorPlusProducts($product);
                }
            }elseif($request->ecommerce == 'pay_dot_com'){
                $type = 'pay_dot_com';
                $parsedData = json_decode($this->parseXml($request), TRUE);
                $products = $this->payDotComProducts($parsedData);

                // PayDotComProduct::truncate();

                foreach ($products as $product) {
                    $product = (object) $product;
                    $this->insertPayDotComProducts($product);
                }
            }

            ImportLog::create([
                'type' => $type
            ]);

            return response()->json(['sucess' => true, 'msg' => 'Products successfully imported']);
        }

        return view('admin.import.index', compact('logs'));
    }

    public function parseXml($request){
        $file = $request->file('product_file');
        $data = simplexml_load_file($file, 'SimpleXMLElement', LIBXML_NOCDATA);
        
        return json_encode($data);
    }

    public function clickBankProducts($clickbank){
        $products = [];

        foreach($clickbank['Category'] as $category){
            foreach ($category['Site'] as $site) {
                $site = (object) $site;

                $product = [
                    'reference_id' => isset($site->Id) && !empty($site->Id) ? $site->Id : '', 
                    'category' => isset($category['Name']) && !empty($category['Name']) ? $category['Name'] : '' ,
                    'popularity_rank' => isset($site->PopularityRank) && !empty($site->PopularityRank) ? $site->PopularityRank : '', 
                    'tile' => isset($site->Title) && !empty($site->Title) ? $site->Title : '', 
                    'description' => isset($site->Description) && !empty($site->Description) ? $site->Description : '', 
                    'has_recurring_products' => isset($site->HasRecurringProducts) && !empty($site->HasRecurringProducts) ? $site->HasRecurringProducts : '', 
                    'gravity' => isset($site->Gravity) && !empty($site->Gravity) ? $site->Gravity : '', 
                    'percent_per_sale' => isset($site->PercentPerSale) && !empty($site->PercentPerSale) ? $site->PercentPerSale : '', 
                    'percent_per_rebill' => isset($site->PercentPerRebill) && !empty($site->PercentPerRebill) ? $site->PercentPerRebill : '', 
                    'average_earnings_per_sale' => isset($site->AverageEarningsPerSale) && !empty($site->AverageEarningsPerSale) ? $site->AverageEarningsPerSale : '', 
                    'initial_earnings_per_sale' => isset($site->InitialEarningsPerSale) && !empty($site->InitialEarningsPerSale) ? $site->InitialEarningsPerSale : '', 
                    'total_rebill_amt' => isset($site->TotalRebillAmt) && !empty($site->TotalRebillAmt) ? $site->TotalRebillAmt : '',
                    'referred' => isset($site->Referred) && !empty($site->Referred) ? $site->Referred : '',
                    'commission' => isset($site->Commission) && !empty($site->Commission) ? $site->Commission : '',
                    'activate_date' => isset($site->ActivateDate) && !empty($site->ActivateDate) ? $site->ActivateDate : ''
                ];

                array_push($products, $product);
            }

            foreach ($category['Category'] as $subcategory) {
                if(isset($subcategory['Site'])){
                    foreach ($subcategory['Site'] as $subsite) {
                        $site = (object) $subsite;
                        $product = [
                            'reference_id' => isset($site->Id) && !empty($site->Id) ? $site->Id : '', 
                            'category' => isset($subcategory['Name']) && !empty($subcategory['Name']) ? $subcategory['Name'] : '',
                            'popularity_rank' => isset($site->PopularityRank) && !empty($site->PopularityRank) ? $site->PopularityRank : '', 
                            'tile' => isset($site->Title) && !empty($site->Title) ? $site->Title : '', 
                            'description' => isset($site->Description) && !empty($site->Description) ? $site->Description : '', 
                            'has_recurring_products' => isset($site->HasRecurringProducts) && !empty($site->HasRecurringProducts) ? $site->HasRecurringProducts : '', 
                            'gravity' => isset($site->Gravity) && !empty($site->Gravity) ? $site->Gravity : '', 
                            'percent_per_sale' => isset($site->PercentPerSale) && !empty($site->PercentPerSale) ? $site->PercentPerSale : '', 
                            'percent_per_rebill' => isset($site->PercentPerRebill) && !empty($site->PercentPerRebill) ? $site->PercentPerRebill : '', 
                            'average_earnings_per_sale' => isset($site->AverageEarningsPerSale) && !empty($site->AverageEarningsPerSale) ? $site->AverageEarningsPerSale : '', 
                            'initial_earnings_per_sale' => isset($site->InitialEarningsPerSale) && !empty($site->InitialEarningsPerSale) ? $site->InitialEarningsPerSale : '', 
                            'total_rebill_amt' => isset($site->TotalRebillAmt) && !empty($site->TotalRebillAmt) ? $site->TotalRebillAmt : '' ,
                            'referred' => isset($site->Referred) && !empty($site->Referred) ? $site->Referred : '',
                            'commission' => isset($site->Commission) && !empty($site->Commission) ? $site->Commission : '',
                            'activate_date' => isset($site->ActivateDate) && !empty($site->ActivateDate) ? $site->ActivateDate : ''
                        ];
                    }
                }

                array_push($products, $product);
            }
        }

        return $products;
    }

    public function insertClickBankProducts($product){
        try{
            if(isset($product->reference_id) && !empty($product->reference_id)){
                $exist = ClickBankProduct::where('reference_id', $product->reference_id)->first();
                if(!isset($exist)){
                    ClickBankProduct::create([
                        'reference_id' => $product->reference_id, 
                        'category' => $product->category,
                        'popularity_rank' => $product->popularity_rank, 
                        'tile' => $product->tile, 
                        'description' => $product->description, 
                        'has_recurring_products' => $product->has_recurring_products, 
                        'gravity' => $product->gravity, 
                        'percent_per_sale' => $product->percent_per_sale, 
                        'percent_per_rebill' => $product->percent_per_rebill, 
                        'average_earnings_per_sale' => $product->average_earnings_per_sale, 
                        'initial_earnings_per_sale' => $product->initial_earnings_per_sale, 
                        'total_rebill_amt' => $product->total_rebill_amt,
                        'referred' => $product->referred,
                        'commission' => $product->commission,
                        'activate_date' => $product->activate_date
                    ]); 
                }
            }
        }catch(\Exception $e){
            return;
        }
    }

    public function payDotComProducts($payDotCom){
        $products = [];

        foreach($payDotCom['offer'] as $data){
            $data = (object)$data;

            $product = [
                'reference_id' => isset($data->id) && !empty($data->id) ? $data->id : '', 
                'name' => isset($data->name) && !empty($data->name) ? $data->name : '',
                'description' => isset($data->description) && !empty($data->description) ? $data->description : '',
                'image' => isset($data->image) && !empty($data->image) ? $data->image : '',
                'payout_type' => isset($data->payout_type) && !empty($data->payout_type) ? $data->payout_type : '',
                'preview_url' => isset($data->preview_url) && !empty($data->preview_url) ? $data->preview_url : '',
                'payout' => isset($data->payout) && !empty($data->payout) ? $data->payout : '',
                'categories' => isset($data->categories) && !empty($data->categories) ? $data->categories : '',
                'request_url' => isset($data->request_url) && !empty($data->request_url) ? $data->request_url : '',
                'recurring' => isset($data->recurring) && !empty($data->recurring) ? $data->recurring : '',
                'recurring_in_funnel' => isset($data->recurring_in_funnel) && !empty($data->recurring_in_funnel) ? $data->recurring_in_funnel : ''
            ];

            array_push($products, $product);
        }

        return $products;
    }

    public function insertPayDotComProducts($product){
        try{
            if(isset($product->reference_id) && !empty($product->reference_id)){
                $exist = PayDotComProduct::where('reference_id', $product->reference_id)->first();
                
                if(!isset($exist)){
                    PayDotComProduct::create([
                        'reference_id' => $product->reference_id,
                        'name' => $product->name,
                        'description' => $product->description,
                        'image' => $product->image,
                        'payout_type' => $product->payout_type,
                        'preview_url' => $product->preview_url,
                        'payout' => $product->payout,
                        'categories' => $product->categories,
                        'request_url' => $product->request_url,
                        'recurring' => $product->recurring,
                        'recurring_in_funnel' => $product->recurring_in_funnel
                    ]);
                }
            }
        }catch(\Exception $e){
            return;
        }
    }

    public function jvzooProducts($file){
        $existing = $this->exisitingtingJvzoo();
        $products = [];
        $productIds = [];

        $fileExtension = $file->getClientOriginalExtension();
        $fileName = 'jvzooproducts' .'.'. $fileExtension;
        $file->move(storage_path('app/imports/'), $fileName);

        $jvzoo = json_decode(File::get(storage_path('app/imports/' . $fileName)));
        
        foreach ($jvzoo->data as $product) {
            $products[$product->product_id] = $product;
            array_push($productIds, $product->product_id);
        }


        // get the product id not existing in the table
        $newProducts = array_diff($productIds, $existing);
        // // get the product id not existing in the new products
        // $oldProducts = array_diff($existing, $productIds);

        return [
            'products' => $products,
            'new_products' => $newProducts,
            // 'old_products' => $oldProducts,
        ];
    }

    public function exisitingtingJvzoo(){
        return JvzooProduct::get()->pluck('reference_id')->toArray();
    }

    public function insertJvzooProducts($item){
        try{
            // JvzooProduct::create([
            //     'reference_id' => isset($product->product_id) && !empty($product->product_id) ? $product->product_id : '',
            //     'product_name' => isset($product->product_name) && !empty($product->product_name) ? $product->product_name : '',
            //     'product_commission' => isset($product->product_commission) && !empty($product->product_commission) ? $product->product_commission : '',
            //     'vendor_name' => isset($product->vendor_name) && !empty($product->vendor_name) ? $product->vendor_name : '',
            //     'launch_date_time' => isset($product->launch_date_time) && !empty($product->launch_date_time) ? $product->launch_date_time : '',
            //     'affiliate_info_page' => isset($product->affiliate_info_page) && !empty($product->affiliate_info_page) ? $product->affiliate_info_page : '',
            //     'sales_page' => isset($product->sales_page) && !empty($product->sales_page) ? $product->sales_page : '',
            //     'product_sales' => isset($product->product_sales) && !empty($product->product_sales) ? $product->product_sales : '',
            //     'product_refund_rate' => isset($product->product_refund_rate) && !empty($product->product_refund_rate) ? $product->product_refund_rate : '',
            //     'product_conversion' => isset($product->product_conversion) && !empty($product->product_conversion) ? $product->product_conversion : '',
            //     'product_epc' => isset($product->product_epc) && !empty($product->product_epc) ? $product->product_epc : '',
            //     'product_average_price' => isset($product->product_average_price) && !empty($product->product_average_price) ? $product->product_average_price : '',
            //     'funnel_sales' => isset($product->funnel_sales) && !empty($product->funnel_sales) ? $product->funnel_sales : '',
            //     'funnel_refund_rate' => isset($product->funnel_refund_rate) && !empty($product->funnel_refund_rate) ? $product->funnel_refund_rate : '',
            //     'funnel_conversion' => isset($product->funnel_conversion) && !empty($product->funnel_conversion) ? $product->funnel_conversion : '',
            //     'funnel_epc' => isset($product->funnel_epc) && !empty($product->funnel_epc) ? $product->funnel_epc : '',
            //     'funnel_average_price' => isset($product->funnel_average_price) && !empty($product->funnel_average_price) ? $product->funnel_average_price : ''
            // ]); 
            JvzooProduct::create([
                'reference_id' => $item->product_id ,
                'product_name' =>  $item->product_name ,
                'product_commission' => $item->product_commission,
                'vendor_name' => $item->vendor_name,
                'launch_date_time' => $item->launch_date_time,
                'affiliate_info_page' => $item->affiliate_info_page,
                'sales_page' => $item->sales_page,
                'product_sales' => $item->product_sales,
                'product_refund_rate' => $item->product_refund_rate,
                'product_conversion' =>$item->product_conversion,
                'product_epc' => $item->product_epc,
                'product_average_price' => $item->product_average_price,
                'funnel_sales' => $item->funnel_sales,
                'funnel_refund_rate' => $item->funnel_refund_rate,
                'funnel_conversion' => $item->funnel_conversion,
                'funnel_epc' => $item->funnel_epc,
                'funnel_average_price' => $item->funnel_average_price
            ]); 
        }catch(\Exception $e){
            return;
        }
    }

    public function warriorPlusProducts($file){
        // $products = [];

        $warriorPlus = json_decode(File::get($file));

        // foreach ($jvzoo->data as $product) {
        //     array_push($products, $product);
        // }

        return $warriorPlus->data;
    }

    public function insertWarriorPlusProducts($product){
        try{
            if(isset($product->offer_code) && !empty($product->offer_code)){
                $exist = WarriorPlusProduct::where('offer_code', $product->offer_code)->first();
                if(!isset($exist)){
                    WarriorPlusProduct::create([
                        'offer_name' => isset($product->offer_name) ? $product->offer_name : '',
                        'offer_date' => isset($product->offer_date) ? $product->offer_date : '', 
                        'offer_code' => isset($product->offer_code) ? $product->offer_code : '', 
                        'offer_url' => isset($product->offer_url) ? $product->offer_url : '',
                        'vendor_name' => isset($product->vendor_name) ? $product->vendor_name : '', 
                        'vendor_url' => isset($product->vendor_url) ? $product->vendor_url : '',
                        'allow_affiliates' => isset($product->allow_affiliates) ? $product->allow_affiliates : '',
                        'request_url' => isset($product->request_url) ? $product->request_url : '',
                        'has_recurring' => isset($product->has_recurring) ? $product->has_recurring : '',
                        'has_contest' => isset($product->has_contest) ? $product->has_contest : '',
                        'sales_range' => isset($product->sales_range) ? $product->sales_range : '',
                        'conv_rate' => isset($product->conv_rate) ? $product->conv_rate : '',
                        'refund_rate' => isset($product->refund_rate) ? $product->refund_rate : '',
                        'visitor_value' => isset($product->visitor_value) ? $product->visitor_value : '',
                        'pulse_score' => isset($product->pulse_score) ? $product->pulse_score : '',
                    ]); 
                }
            }
            
        }catch(\Exception $e){
            return;
        }
    }

    public function importRedirect(){
        Session::flash('success', 'Products successfully imported!');
        return redirect()->route('import.index');
    }
}
