<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeoSettingsAnalyticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_settings_analytics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->longText('google_analytics_tracking_code')->nullable();
            $table->longText('third_party_analytics_tracking_code')->nullable();
            $table->longText('facebook_remarketing_pixel_script')->nullable();
            $table->longText('webengage_tracking_id')->nullable();
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
        Schema::dropIfExists('seo_settings_analytics');
    }
}
