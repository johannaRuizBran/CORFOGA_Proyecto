<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailsTable extends Migration
{
    /**
     * Crea la migración.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('details', function (Blueprint $table) {
            $table->integer('inspectionID')->unsigned();
            $table->integer('animalID')->unsigned();
            $table->tinyInteger('feedingMethodID')->unsigned();
            $table->string('weight', 6);
            $table->string('scrotalCircumference', 5);
            $table->string('observations');

            $table->primary(['inspectionID', 'animalID']);
            $table->foreign('inspectionID')->references('id')->on('inspections');
            $table->foreign('animalID')->references('id')->on('animals');
            $table->index('feedingMethodID');
        });
    }

    /**
     * Revierte la migración.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('details');
    }
}
