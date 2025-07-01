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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->bigIncrements('id_notifikasi');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->unsignedBigInteger('id_user');
            $table->string('jenis_kirim', 50);
            $table->timestamp('waktu_kirim')->nullable();
            $table->text('isi_pesan');
            $table->boolean('status_kirim')->default(0);

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
        Schema::dropIfExists('notifikasi');
    }
};
