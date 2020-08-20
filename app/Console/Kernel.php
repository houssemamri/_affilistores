<?php

namespace App\Console;

use Illuminate\Support\Facades\Config;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\LogSample;
use App\Store;
use DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\InsertLog',
        'App\Console\Commands\PublishProduct',
        'App\Console\Commands\ShareSocialCampaign',
        'App\Console\Commands\AmazonGetProducts',
        'App\Console\Commands\EbayGetProducts',
        'App\Console\Commands\AliExpressGetProducts',
        'App\Console\Commands\WalmartGetProducts',
        'App\Console\Commands\ShopComGetProducts',
        'App\Console\Commands\CjComGetProducts',
        'App\Console\Commands\JvzooGetProducts',
        'App\Console\Commands\ClickBankGetProducts',
        'App\Console\Commands\WarriorPlusGetProducts',
        'App\Console\Commands\PayDotComGetProducts',
        'App\Console\Commands\ImportProductFeed',
        'App\Console\Commands\BlogAutomation',
        'App\Console\Commands\BlogAutomationWeekly',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        //automate blog posting
        $schedule->command('blog:automation')->daily()->appendOutputTo(storage_path('logs/blog_automation.log'));
        $schedule->command('blog:automationweekly')->weekly()->appendOutputTo(storage_path('logs/blog_automation.log'));

        //share products on social media sites
        $schedule->command('product:post')->hourly()->appendOutputTo(storage_path('logs/social_campaign.log'));

        //get products from affiliates and insert them
        $schedule->command('product:amazon')->dailyAt('16:00')->appendOutputTo(storage_path('logs/amazon.log'));
        $schedule->command('product:ebay')->dailyAt('16:30')->appendOutputTo(storage_path('logs/ebay.log'));
        $schedule->command('product:aliexpress')->dailyAt('17:00')->appendOutputTo(storage_path('logs/aliexpress.log'));
        $schedule->command('product:walmart')->dailyAt('17:30')->appendOutputTo(storage_path('logs/walmart.log'));
        $schedule->command('product:shopcom')->dailyAt('18:00')->appendOutputTo(storage_path('logs/shopcom.log'));
        $schedule->command('product:cjcom')->dailyAt('18:30')->appendOutputTo(storage_path('logs/cjcom.log'));
        $schedule->command('product:jvzoo')->dailyAt('19:00')->appendOutputTo(storage_path('logs/jvzoo.log'));
        $schedule->command('product:clickbank')->dailyAt('19:30')->appendOutputTo(storage_path('logs/clickbank.log'));
        $schedule->command('product:warriorplus')->dailyAt('20:00')->appendOutputTo(storage_path('logs/warriorplus.log'));
        $schedule->command('product:paydotcom')->dailyAt('20:30')->appendOutputTo(storage_path('logs/paydotcom.log'));
        
        //published products
        $schedule->command('publish:product')->twiceDaily(7, 20)->appendOutputTo(storage_path('logs/publish_product.log'));;
        
        //update products for jvzoo, clickbank, paydotcom, warrior plus
        $schedule->command('import:products')->dailyAt('01:00')->appendOutputTo(storage_path('logs/import_products.log'));

        //generate backup
        $schedule->command('backup:run')->dailyAt(11)->appendOutputTo(storage_path('logs/backup.log'));;
        $schedule->command('backup:clean')->dailyAt(11)->appendOutputTo(storage_path('logs/backup.log'));;
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }

    public function getAllAutomations(){
        $stores = Store::all();

        foreach ($stores as $store) {
            //sharing of products
            //
        }
    }
}
