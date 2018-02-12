<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimalsTable extends Migration
{
    /**
     * Crea la migración.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('asocebuFarmID')->unsigned();
            $table->tinyInteger('breedID')->unsigned();
            $table->string('register', 15)->unique();
            $table->string('code', 15);
            $table->enum('sex', ['m', 'h']);
            $table->date('birthdate');
            $table->string('fatherRegister', 15);
            $table->string('fatherCode', 15);
            $table->string('motherRegister', 15);
            $table->string('motherCode', 15);

            $table->foreign('asocebuFarmID')->references('asocebuID')->on('farms');
            $table->foreign('breedID')->references('id')->on('breeds');
            $table->index(['asocebuFarmID', 'breedID', 'sex']);
        });
    }

    /**
     * Revierte la migración.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animals');
    }
}
