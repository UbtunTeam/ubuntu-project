<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('farmer_id')->unsigned();
            $table->foreign('farmer_id')->references('id')->on('users');
            $table->string('farm_location');
            $table->string('landscape');
            $table->string('longitude');
            $table->string('latitude');
            $table->string('farm_description');
            $table->string('farm_image');
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
        Schema::dropIfExists('farms');
    }
}
