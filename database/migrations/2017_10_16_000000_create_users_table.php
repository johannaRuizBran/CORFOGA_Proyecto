<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Crea la migración.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('identification', 12)->unique();
            $table->string('name', 30);
            $table->string('lastName', 30);
            $table->string('password', 60);
            $table->string('email')->unique();
            $table->string('phoneNumber', 9)->nullable();
            $table->enum('role', ['a', 'i', 'p']);
            $table->enum('state', ['1', '0']);
            $table->rememberToken();
            $table->timestamps();
            $table->index('identification');
        });
        // Se insertan los valores por defecto de la tabla.
        DB::table('users')->insert([
            'identification' => '0-0000-0000',
            'name' => 'Admin',
            'lastName' => 'Corfoga',
            'password' => '$2b$10$YLI6p8Ntje.muyfpdjepN.myjythc8CZTlUmvyI8ybPDGNvHEARJu',
            'email' => 'admin@corfoga.com',
            'phoneNumber' => '0000-0000',
            'role' => 'a',
            'state' => '1'
        ]);
    }

    /**
     * Revierte la migración.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
