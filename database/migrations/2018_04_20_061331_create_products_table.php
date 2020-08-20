
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->mediumText('reference_id');
            $table->longText('name');
            $table->longText('description');
            $table->mediumText('permalink')->nullable();
            $table->mediumText('details_link')->nullable();
            $table->text('image');
            $table->string('currency')->default('USD');
            $table->decimal('price', 10, 2);
            $table->string('source');
            $table->dateTime('published_date');
            $table->integer('auto_approve')->default(0);
            $table->integer('show_tweets')->default(1);
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('products');
    }
}
