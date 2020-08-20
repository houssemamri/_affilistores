<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;
use Zipper;
use SimpleXMLElement;
use Session;
use GuzzleHttp\Client;
use App\ClickBankProduct;
use App\JvzooProduct;
use App\WarriorPlusProduct;
use App\PayDotComProduct;
use App\ImportLog;

class ImportProductFeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Products from JVZoo, ClickBank, Warrior Plus, and PayDotCom';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('memory_limit', '-1'); 
        $marketPlaces = ['jvzoo', 'click_bank', 'warrior_plus', 'pay_dot_com'];

        foreach ($marketPlaces as $marketPlace) {
            if($marketPlace == 'jvzoo'){
                $this->importJvzoo();
                ImportLog::create(['type' => $marketPlace]);
            }else if($marketPlace == 'click_bank'){
                $this->importClickBank();
                ImportLog::create(['type' => $marketPlace]);
            }elseif($marketPlace == 'pay_dot_com'){
                $this->importPayDotCom();
                ImportLog::create(['type' => $marketPlace]);
            }elseif($marketPlace == 'warrior_plus'){
                $this->importWarriorPlus();
                ImportLog::create(['type' => $marketPlace]);
            }
        }
    }

    public function importJvzoo(){
        $feed = $this->getJvzooProductFeed();
        $products = $this->jvzooProducts($feed);
        foreach ($products['new_products'] as $newProduct) {
            $insert = $this->insertJvzooProducts($products['products'][$newProduct]);
        }
    }
    
    public function getJvzooProductFeed(){
        $url = "https://jvzdownloads.s3.us-east-2.amazonaws.com/findproducts.zip";
        $path = storage_path('app/zip/jvzoo.zip');

        $client = new Client();

        $response = $client->request(
            'GET', $url, ['sink' => $path]
        );

        Zipper::make($path)->extractTo('storage/app/zip/');

        return storage_path('app/zip/findproducts.json');
    }

    public function jvzooProducts($file){
        $existing = $this->exisitingtingJvzoo();
        $products = [];
        $productIds = [];

        $jvzoo = json_decode(File::get($file));
        
        foreach ($jvzoo->data as $product) {
            $products[$product->product_id] = $product;
            array_push($productIds, $product->product_id);
        }

        // get the product id not existing in the table
        $newProducts = array_diff($productIds, $existing);

        return [
            'products' => $products,
            'new_products' => $newProducts,
        ];
    }

    public function exisitingtingJvzoo(){
        return JvzooProduct::get()->pluck('reference_id')->toArray();
    }

    public function insertJvzooProducts($item){
        try{
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

    public function importClickBank(){
        $feed = $this->getClickBankFeed();
        $clickbank = json_decode($this->parseXml($feed), TRUE);
        $products = $this->clickBankProducts($clickbank);

        foreach ($products as $product) {
            $product = (object) $product;
            $this->insertClickBankProducts($product);
        }
    }

    public function getClickBankFeed(){
        $url = "https://accounts.clickbank.com/feeds/marketplace_feed_v2.xml.zip";
        $path = storage_path('app/zip/clickbank.zip');

        $client = new Client();

        $response = $client->request(
            'GET', $url, ['sink' => $path]
        );

        Zipper::make($path)->extractTo('storage/app/zip/');

        return storage_path('app/zip/marketplace_feed_v2.xml');
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

    public function importPayDotCom(){
        $feed = $this->getPaydotComFeed();
        $parsedData = json_decode($this->parseXml($feed), TRUE);
        $products = $this->payDotComProducts($parsedData);

        foreach ($products as $product) {
            $product = (object) $product;
            $this->insertPayDotComProducts($product);
        }

    }

    public function getPaydotComFeed(){
        $url = "https://app.paydotcom.com/xml_feed/public";
        $path = storage_path('app/zip/paydotcom.zip');

        $client = new Client();

        $response = $client->request(
            'GET', $url, ['sink' => $path]
        );

        Zipper::make($path)->extractTo('storage/app/zip/');

        return storage_path('app/zip/paydotcom_public.xml');
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
    
    public function importWarriorPlus(){
        $feed = $this->getWarriorPlusFeed();
        $products = $this->warriorPlusProducts($feed);

        foreach ($products as $product) {
            $this->insertWarriorPlusProducts((object) $product);
        }
    }

    public function getWarriorPlusFeed(){
        $url = "https://warriorplus.com/feeds/warriorplus-marketplace-feed.zip";
        $path = storage_path('app/zip/warriorplus.zip');

        $client = new Client();

        $response = $client->request(
            'GET', $url, ['sink' => $path]
        );

        Zipper::make($path)->extractTo('storage/app/zip/');

        return storage_path('app/zip/warriorplus-marketplace-feed.json');
    }

    public function warriorPlusProducts($file){
        $warriorPlus = json_decode(File::get($file));
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

    public function parseXml($file){
        $data = simplexml_load_file($file);
        return json_encode($data);
    }
}
