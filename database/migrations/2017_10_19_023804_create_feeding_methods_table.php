<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateFeedingMethodsTable extends Migration
{
    /**
     * Crea la migración.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feeding_methods', function (Blueprint $table) {
            $table->tinyInteger('id')->primary()->unsigned();
            $table->string('name', 25);
        });
        // Se insertan los valores por defecto de la tabla.
        DB::table('feeding_methods')->insert([
            ['id' => 0, 'name' => 'Desconocido'],
            ['id' => 1, 'name' => 'Pastoreo'],
            ['id' => 2, 'name' => 'Estabulación'],
            ['id' => 3, 'name' => 'Semi estabulación'],
            ['id' => 4, 'name' => 'Suplementación en potrero']
        ]);
    }

    /**
     * Revierte la migración.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feeding_methods');
    }
}
