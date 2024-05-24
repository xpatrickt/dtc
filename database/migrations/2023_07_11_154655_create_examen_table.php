<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_persona_convocatoria');
            $table->double('total_fase1')->nullable();
            $table->double('nota_examen')->nullable();
            $table->double('ponderado1')->nullable();
            $table->double('ponderado2')->nullable();
            $table->double('ponderado3')->nullable();
            $table->double('fase2_ponderado')->nullable();
            $table->boolean('estado')->nullable();

            $table->foreign('id_persona_convocatoria')->references('id')->on('persona_convocatoria');

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
        Schema::dropIfExists('examen');
    }
}
