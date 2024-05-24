<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapacitacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capacitacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_persona_convocatoria');
            $table->double('cap_c1')->nullable();
            $table->double('cap_c2')->nullable();
            $table->double('cap_c3')->nullable();
            $table->double('cap_c4')->nullable();
            $table->double('cap_c5')->nullable();
            $table->double('cap_c6')->nullable();
            $table->double('cap_c7')->nullable();
            $table->double('cap_c8')->nullable();
            $table->integer('asiste_d1')->nullable();
            $table->integer('asiste_d2')->nullable();
            $table->integer('asiste_d3')->nullable();
            $table->integer('asiste_d4')->nullable();
            $table->integer('asiste_d5')->nullable();
            $table->double('suma_total1')->nullable();
            $table->double('suma_total2')->nullable();
            $table->string('estado_capa1',15)->nullable();
            $table->string('estado_capa2',15)->nullable();
            $table->text('observacion')->nullable();
            $table->string('condicion',15)->nullable();
            $table->boolean('estado');
            $table->double('ponderado');
            $table->double('estado_capa_total');
            $table->integer('aula');

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
        Schema::dropIfExists('capacitacion');
    }
}
