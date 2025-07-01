<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->bigIncrements('id_mahasiswa');
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_angkatan');
            $table->string('no_hp_orang_tua', 20)->nullable();
            $table->string('email_ortu', 100)->nullable();
            $table->string('status_kelulusan', 20)->nullable();

            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
            $table->foreign('id_angkatan')->references('id_angkatan')->on('angkatan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mahasiswa');
    }
};
