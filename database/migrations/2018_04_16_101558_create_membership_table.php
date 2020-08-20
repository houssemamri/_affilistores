<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembershipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->increments('id');
            $table->string('jvzoo_product_id');
            $table->string('title');
            $table->string('upgrade_membership_url')->nullable();
            $table->integer('next_upgrade_membership_id')->nullable();
            $table->decimal('product_price', 10, 2)->nullable()->default(0);	
            $table->integer('frequency')->nullable();
            $table->string('trial_period')->nullable();
            $table->decimal('trial_price', 10, 2)->nullable()->default(0);
            $table->integer('stores_per_month')->default(5);
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
        Schema::dropIfExists('membership');
    }
}
