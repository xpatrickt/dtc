<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class CreatePermisoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permiso', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50);
            $table->string('slug', 50);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();          
            $table->integer('deleted_by')->nullable();

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permiso');
    }
}
