<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Product;

class PublishProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:product';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish products';

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
        $products = Product::whereDate('published_date', '<=', date('Y-m-d'))->where('status', 0)->update([
            'status' => 1
        ]);
    }
}
