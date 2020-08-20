<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClickBankProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('click_bank_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_id')->nullable();
            $table->string('category')->nullable();
            $table->string('popularity_rank')->nullable();
            $table->text('title')->nullable();
            $table->mediumText('description')->nullable();
            $table->string('has_recurring_products')->nullable();
            $table->string('gravity')->nullable();
            $table->string('percent_per_sale')->nullable();
            $table->string('percent_per_rebill')->nullable();
            $table->string('average_earnings_per_sale')->nullable();
            $table->string('initial_earnings_per_sale')->nullable();
            $table->string('total_rebill_amt')->nullable();
            $table->string('referred')->nullable();
            $table->string('commission')->nullable();
            $table->string('activate_date')->nullable();
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
        Schema::dropIfExists('click_bank_products');
    }
}
