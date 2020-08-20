<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('image')->nullable();
            $table->string('main_tagline')->nullable();
            $table->string('main_tagline_font_size')->nullable();
            $table->mediumText('sub_tagline')->nullable();
            $table->string('sub_tagline_font_size')->nullable();
            $table->text('cta_button_one_text')->nullable();
            $table->mediumText('cta_button_one_link')->nullable();
            $table->text('cta_button_two_text')->nullable();
            $table->mediumText('cta_button_two_link')->nullable();
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('sliders');
    }
}
