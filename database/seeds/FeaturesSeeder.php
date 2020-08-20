<?php

use Illuminate\Database\Seeder;
use App\Feature;

class FeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $features = [
            (object) [
                "name" => "Amazon",
                "type" => "affiliate_store"
            ],
            (object) [
                "name" => "Ebay",
                "type" => "affiliate_store"
            ],
            (object) [
                "name" => "AliExpress",
                "type" => "affiliate_store"
            ],
            (object) [
                "name" => "Walmart",
                "type" => "affiliate_store"
            ],
            (object) [
                "name" => "Shop.com",
                "type" => "affiliate_store"
            ],
            (object) [
                "name" => "Cj.com",
                "type" => "affiliate_store"
            ],
            (object) [
                "name" => "YouTube Videos",
                "type" => "product_feature"
            ],
            (object) [
                "name" => "Related Tweets",
                "type" => "product_feature"
            ],
            (object) [
                "name" => "Facebook Pixels",
                "type" => "store_feature"
            ],
            (object) [
                "name" => "Google AdSense",
                "type" => "store_feature"
            ],
            (object) [
                "name" => "Amazon Reviews",
                "type" => "product_feature"
            ],
            (object) [
                "name" => "Price Drops",
                "type" => "product_feature"
            ],
            (object) [
                "name" => "Sharing to Social Media Platforms",
                "type" => "product_feature"
            ],
            (object) [
                "name" => "Facebook Customer Messenger Bot",
                "type" => "store_feature"
            ],
            (object) [
                "name" => "Pinger Service",
                "type" => "store_feature"
            ],
            (object) [
                "name" => "FB Group Finder",
                "type" => "store_feature"
            ],
            (object) [
                "name" => "Social Proofs",
                "type" => "store_feature"
            ],
            (object) [
                "name" => "Exit Pops",
                "type" => "store_feature"
            ]
        ];

        foreach ($features as $feature) {
            Feature::create([
                'name' => $feature->name,
                'type' => $feature->type
            ]);
        }
    }
}
