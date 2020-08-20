<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarriorPlusProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warrior_plus_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_id')->nullable();
            $table->text('offer_name')->nullable();
            $table->string('offer_date')->nullable();
            $table->string('offer_code')->nullable();
            $table->text('offer_url')->nullable();
            $table->text('vendor_name')->nullable();
            $table->text('vendor_url')->nullable();
            $table->string('allow_affiliates')->nullable();
            $table->text('request_url')->nullable();
            $table->string('has_recurring')->nullable();
            $table->string('has_contest')->nullable();
            $table->string('sales_range')->nullable();
            $table->string('conv_rate')->nullable();
            $table->string('refund_rate')->nullable();
            $table->string('visitor_value')->nullable();
            $table->string('pulse_score')->nullable();
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
        Schema::dropIfExists('warrior_plus_products');
    }
}
