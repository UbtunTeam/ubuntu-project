<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farm_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('farm_id')->unsigned();
            $table->foreign('farm_id')->references('id')->on('farms');
            $table->unsignedBigInteger('stock_id');
            $table->foreign('stock_id')->references('id')->on('stocks');
            $table->string('product_name');
            $table->float('price', 16,2);
            $table->dateTime('plant_date');
            $table->dateTime('harvest_date');
            $table->string('description');
            $table->string('product_image');
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
        Schema::dropIfExists('farm_products');
    }
}
