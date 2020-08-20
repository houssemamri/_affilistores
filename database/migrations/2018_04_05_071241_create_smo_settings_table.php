<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmoSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smo_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('store_id');
            $table->string('name');
            $table->string('page_url')->nullable();
            $table->string('design_options')->nullable();
            $table->string('display_options')->nullable();
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
        //
    }
}
