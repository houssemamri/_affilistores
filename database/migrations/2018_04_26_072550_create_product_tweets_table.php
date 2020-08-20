<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTweetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_tweets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id');
            $table->mediumText('tweet_id');
            $table->string('user');
            $table->longText('content');
            $table->mediumText('user_profile_img');
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
        Schema::dropIfExists('product_tweets');
    }
}
