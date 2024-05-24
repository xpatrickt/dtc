<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_persona_convocatoria');
            $table->string('rnp',2)->nullable();
            $table->string('office',2)->nullable();
            $table->string('certificado_lengua',2)->nullable();
            $table->string('profesion',250)->nullable();
            $table->string('grado',50)->nullable();
            $table->string('criterio_cv_1',5)->nullable();
            $table->string('criterio_cv_2',5)->nullable();
            $table->string('criterio_cv_3',5)->nullable();
            $table->string('criterio_cv_4',5)->nullable();
            $table->string('criterio_cv_5',5)->nullable();
            $table->string('criterio_cv_6',5)->nullable();
            $table->double('ponderado_1')->nullable();
            $table->double('ponderado_2')->nullable();
            $table->double('ponderado_3')->nullable();
            $table->double('ponderado_4')->nullable();
            $table->double('ponderado_5')->nullable();
            $table->double('ponderado_6')->nullable();
            $table->double('total_ponderado')->nullable();
            $table->string('estado_cv',15)->nullable();
            $table->string('resultado_cv',15)->nullable();
            $table->integer('num_registro')->nullable();
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
        Schema::dropIfExists('evaluacion');
    }
}
