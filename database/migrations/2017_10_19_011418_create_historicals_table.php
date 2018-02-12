<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoricalsTable extends Migration
{
    /**
     * Crea la migración.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historicals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userID')->unsigned();
            $table->tinyInteger('typeID')->unsigned();
            $table->dateTime('datetime');
            $table->string('description');

            $table->foreign('userID')->references('id')->on('users');
            $table->foreign('typeID')->references('id')->on('types');
            $table->index(['dateTime', 'userID', 'typeID']);
        });
    }

    /**
     * Revierte la migración.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historicals');
    }
}
