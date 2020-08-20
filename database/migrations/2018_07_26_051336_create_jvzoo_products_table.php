<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJvzooProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jvzoo_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_id')->nullable();
            $table->text('product_name')->nullable();
            $table->string('product_commission')->nullable();
            $table->text('vendor_name')->nullable();
            $table->string('launch_date_time')->nullable();
            $table->text('affiliate_info_page')->nullable();
            $table->text('sales_page')->nullable();
            $table->string('product_sales')->nullable();
            $table->string('product_refund_rate')->nullable();
            $table->string('product_conversion')->nullable();
            $table->string('product_epc')->nullable();
            $table->string('product_average_price')->nullable();
            $table->string('funnel_sales')->nullable();
            $table->string('funnel_refund_rate')->nullable();
            $table->string('funnel_conversion')->nullable();
            $table->string('funnel_epc')->nullable();
            $table->string('funnel_average_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jvzoo_products');
    }
}
