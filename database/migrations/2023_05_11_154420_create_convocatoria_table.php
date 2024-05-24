<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConvocatoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('convocatoria', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cargo')->nullable();
            $table->string('codigo',5)->nullable();
            $table->string('nombre',100)->nullable();

            $table->foreign('id_cargo')->references('id')->on('cargo');

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
        Schema::dropIfExists('convocatoria');
    }
}
