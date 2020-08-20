<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogFeedAutomationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_feed_automations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('blog_feed_id');
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->string('frequency')->nullable();
            $table->integer('auto_publish')->default(0);
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
        Schema::dropIfExists('blog_feed_automations');
    }
}
