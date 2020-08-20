<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Store;
use App\LogSample;

class InsertLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Insert:log';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert some sample logs';

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
        $stores = Store::all();

        foreach ($stores as $store) {
            LogSample::create([
                'store_id' => $store->id,
                'time' =>  date('H:i a'),
                'text' => 'This is a test @' . date('Y-m-d H:i:s')
            ]);
        }
    }
}
