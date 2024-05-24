<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class CreatePermisoRolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permiso_rol', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permiso_id')->nullable();
            $table->unsignedBigInteger('rol_id')->nullable();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();          
            $table->integer('deleted_by')->nullable();

            $table->foreign('permiso_id')->references('id')->on('permiso');
            $table->foreign('rol_id')->references('id')->on('rol');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permiso_rol');
    }
}
