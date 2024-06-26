<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProyectoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proyecto', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 250);
            $table->string('descripcion', 50)->nullable();
            $table->string('apellido_mat', 50)->nullable();
            $table->string('sexo', 50)->nullable();
            $table->string('grado', 50)->nullable();
            $table->string('profesion', 50)->nullable();
            $table->string('telefono2', 50)->nullable();
            $table->date('fecha_nac')->nullable();
            $table->string('documento')->nullable();
            $table->string('direccion',225)->nullable();
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
        Schema::dropIfExists('proyecto');
    }
}
