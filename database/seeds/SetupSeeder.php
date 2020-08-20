<?php

use Illuminate\Database\Seeder;
use App\Setup;

class SetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setups = [
            (object) [
                "name" => "Site Logo",
                "key" => "logo",
                "value" => "IMG_1523871466.png",
                "help" => "",
                "type" => "file"
            ],
            (object) [
                "name" => "Site Favicon",
                "key" => "favicon",
                "value" => "FAVICON_1529485472.png",
                "help" => "",
                "type" => "file"
            ],
            (object) [
                "name" => "Site Name",
                "key" => "site_name",
                "value" => "Instant Ecom Lab",
                "help" => "",
                "type" => "text"
            ],
            (object) [
                "name" => "Site Description",
                "key" => "site_description",
                "value" => "<p>Instant Ecom Lab - Sell Affiliate Products Instantly!</p>",
                "help" => "",
                "type" => "textarea"
            ],
            (object) [
                "name" => "JVZIPN Secret Key",
                "key" => "jvzipn",
                "value" => "d741a6139efbb42773a399576606b87438177b8d38b8b4aea879167291801616",
                "help" => "",
                "type" => "text"
            ],
            (object) [
                "name" => "GetResponse API Key",
                "key" => "api_key",
                "value" => null,
                "help" => "",
                "type" => "text"
            ],
            (object) [
                "name" => "GetResponse Campaign Name",
                "key" => "campaign_name",
                "value" => null,
                "help" => "",
                "type" => "text"
            ],
            (object) [
                "name" => "Welcome Message",
                "key" => "welcome_message",
                "value" => "<p>Welcome to Instant Ecom Lab!</p>",
                "help" => "",
                "type" => "textarea"
            ],
            (object) [
                "name" => "Login",
                "key" => "login_message",
                "value" => "<p></p><h1><b>Join Our Community</b></h1><p><span style='font-weight: normal;'>Lorem ipsum dolor sit amet, cu his mutat populo accommodare. Stet alterum an eam. Mea cu commodo tamquam, est animal epicuri epicurei no, commodo tamquam eos ad.</span></p><p></p>",
                "help" => "",
                "type" => "textarea"
            ],
            (object) [
                "name" => "Pixabay API Key",
                "key" => "pixabay",
                "value" => "1021219-8cbb128d39535663b1d408c9a",
                "help" => "",
                "type" => "text"
            ],
        ];


        foreach ($setups as $setup) {
            Setup::create([
                'name' => $setup->name,
                'key' => $setup->key,
                'value' => $setup->value,
                'help' => $setup->help,
                'type' => $setup->type
            ]);
        }
    }
}
