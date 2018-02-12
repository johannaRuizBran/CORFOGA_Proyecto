<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->integer('asocebuID')->primary()->unsigned();
            $table->integer('userID')->unsigned();
            $table->tinyInteger('regionID')->unsigned();
            $table->string('name', 100);
            $table->enum('state', ['1', '0']);
            $table->timestamps();

            $table->foreign('userID')->references('id')->on('users');
            $table->foreign('regionID')->references('id')->on('regions');
            $table->index(['asocebuID', 'userID', 'regionID']);
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
