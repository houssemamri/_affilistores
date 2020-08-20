<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreLegalPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_legal_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->longText('terms_conditions')->nullable();
            $table->longText('privacy_policy')->nullable();
            $table->longText('contact_us')->nullable();
            $table->longText('gdpr_compliance')->nullable();
            $table->longText('affiliate_disclosure')->nullable();
            $table->longText('cookie_policy')->nullable();
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
        Schema::dropIfExists('store_legal_pages');
    }
}
