<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ipns', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sys')->nullable();
            $table->string('ctransreceipt')->nullable();
            $table->string('ccustemail')->nullable();
            $table->string('ccustname')->nullable();
            $table->string('ctransvendor')->nullable();
            $table->string('cproditem')->nullable();
            $table->string('cprodtype')->nullable();
            $table->string('ctransaction')->nullable();
            $table->string('ctransamount')->nullable();
            $table->string('ctranstime')->nullable();
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
        Schema::dropIfExists('ipns');
    }
}
