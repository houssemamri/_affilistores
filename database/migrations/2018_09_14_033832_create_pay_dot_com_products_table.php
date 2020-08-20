<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayDotComProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_dot_com_products', function (Blueprint $table) {
            $table->increments('id');
            $table->text('reference_id')->nullable();
            $table->text('name')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('payout_type')->nullable();
            $table->text('preview_url')->nullable();
            $table->string('payout')->nullable();
            $table->string('categories')->nullable();
            $table->text('request_url')->nullable();
            $table->string('recurring')->nullable();
            $table->string('recurring_in_funnel')->nullable();
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
        Schema::dropIfExists('pay_dot_com_products');
    }
}
