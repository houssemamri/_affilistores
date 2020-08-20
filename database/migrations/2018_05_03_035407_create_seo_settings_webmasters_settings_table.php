<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeoSettingsWebmastersSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_settings_webmasters_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->mediumText('google_verification_code')->nullable();
            $table->mediumText('bing_verification_code')->nullable();
            $table->mediumText('pinterest_verification_code')->nullable();
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
        Schema::dropIfExists('seo_settings_webmasters_settings');
    }
}
