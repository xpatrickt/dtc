<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntrevistaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrevista', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_persona_convocatoria');
            $table->double('total_fase2')->nullable();
            $table->integer('presentacion_personal')->nullable();
            $table->integer('conocimiento_vocacion')->nullable();
            $table->integer('e_pregunta1')->nullable();
            $table->integer('e_pregunta2')->nullable();
            $table->integer('e_pregunta3')->nullable();
            $table->integer('e_pregunta4')->nullable();
            $table->integer('e_pregunta5')->nullable();
            $table->integer('lenguaje_voz')->nullable();
            $table->integer('total_entrevista')->nullable();
            $table->double('fase3_ponderado')->nullable();
            $table->string('estado',15)->nullable();
            $table->string('resultado',15)->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('estado');


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
        Schema::dropIfExists('entrevista');
    }
}
