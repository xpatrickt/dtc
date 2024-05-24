<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsistenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asistencia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_persona_convocatoria');
            $table->string('local_capacitacion',100)->nullable();
            $table->string('aula',2)->nullable();
            $table->string('hora_tablet',2)->nullable();
            $table->integer('estado')->nullable();
            
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
        Schema::dropIfExists('asistencia');
    }
}
