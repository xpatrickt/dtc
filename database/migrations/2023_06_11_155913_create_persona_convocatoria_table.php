<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonaConvocatoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persona_convocatoria', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_persona');
            $table->unsignedBigInteger('id_convocatoria');
            $table->unsignedBigInteger('id_sede_provincial');
            $table->boolean('estado');

            $table->foreign('id_persona')->references('id')->on('persona');
            $table->foreign('id_convocatoria')->references('id')->on('convocatoria');
            $table->foreign('id_sede_provincial')->references('id')->on('sede_provincial');
            
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
        Schema::dropIfExists('persona_convocatoria');
    }
}
