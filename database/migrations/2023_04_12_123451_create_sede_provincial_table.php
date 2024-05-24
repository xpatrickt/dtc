<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSedeProvincialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sede_provincial', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_sede',50)->nullable();
            $table->string('codigo_sede',50)->nullable();
            $table->unsignedBigInteger('id_sede_regional');
            $table->foreign('id_sede_regional')->references('id')->on('sede_regional');
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
        Schema::dropIfExists('sede_provincial');
    }
}
