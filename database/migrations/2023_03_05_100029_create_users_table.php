<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('usuario', 100)->nullable();
            $table->string('nombre', 100)->unique();
            $table->string('password', 100);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('provider',15)->nullable();
            $table->string('provider_id',15)->nullable();
            $table->string('provider_token',400)->nullable();
            $table->string('provider_expires_in',10)->nullable();

            $table->boolean('activo')->default(true);
            $table->boolean('es_admin')->default(false);
            $table->tinyInteger('tipo_usuario')->nullable();//1 admin //2 gestor
            $table->timestamp('ult_visita')->nullable();
            $table->rememberToken();
            $table->unsignedBigInteger('id_persona')->nullable();
            $table->timestamps();

            $table->foreign('id_persona')->references('id')->on('persona');

        });
        DB::table('users')->insert(array('nombre'=>'admin', 'email'=>'admin@admin.com', 'password'=>'$2y$10$ga0HxfpNEF7kRpxciepzC.l4scufJDPs9S7Qk000Zmt/itbtJmz.O', 'es_admin'=>true, 'tipo_usuario' => 1, 'id_persona' => 1));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
