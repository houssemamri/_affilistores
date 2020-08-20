<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeoSettingsArchivePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_settings_archive_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->text('meta_title')->nullable();
            $table->integer('robots_meta_no_index')->default(0);
            $table->integer('robots_meta_no_follow')->default(0);
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
        Schema::dropIfExists('seo_settings_archive_pages');
    }
}
