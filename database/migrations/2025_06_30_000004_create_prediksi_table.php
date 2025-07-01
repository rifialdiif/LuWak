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
        Schema::create('prediksi', function (Blueprint $table) {
            $table->bigIncrements('id_prediksi');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->unsignedBigInteger('id_user');
            $table->date('tanggal_prediksi');
            $table->string('hasil_prediksi', 50);
            $table->float('confidence_score')->nullable();

            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prediksi');
    }
};
