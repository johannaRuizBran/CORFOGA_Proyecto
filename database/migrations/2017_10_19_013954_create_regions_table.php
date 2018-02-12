<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateRegionsTable extends Migration
{
    /**
     * Crea la migración.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->tinyInteger('id')->primary()->unsigned();
            $table->string('name', 16);
        });
        // Se insertan los valores por defecto de la tabla.
        DB::table('regions')->insert([
            ['id' => 1, 'name' => 'Central'],
            ['id' => 2, 'name' => 'Chorotega'],
            ['id' => 3, 'name' => 'Pacífico Central'],
            ['id' => 4, 'name' => 'Brunca'],
            ['id' => 5, 'name' => 'Huetar Atlántica'],
            ['id' => 6, 'name' => 'Huetar Norte']
        ]);
    }

    /**
     * Revierte la migración.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regions');
    }
}
