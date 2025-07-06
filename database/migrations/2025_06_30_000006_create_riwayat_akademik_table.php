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
        Schema::create('riwayat_akademik', function (Blueprint $table) {
            $table->bigIncrements('id_riwayat');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->float('ips_semester_1')->nullable();
            $table->float('ips_semester_2')->nullable();
            $table->float('ips_semester_3')->nullable();
            $table->float('ips_semester_4')->nullable();
            $table->string('status_semester_1', 20)->nullable();
            $table->string('status_semester_2', 20)->nullable();
            $table->string('status_semester_3', 20)->nullable();
            $table->string('status_semester_4', 20)->nullable();
            $table->integer('total_sks_lulus')->nullable();
            $table->integer('total_sks_tidak_lulus')->nullable();
            $table->string('dokumen_transkrip')->nullable();
            $table->string('status_validasi', 20)->nullable();
            $table->unsignedBigInteger('validasi_by')->nullable();
            $table->dateTime('validasi_at')->nullable();

            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('mahasiswa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('riwayat_akademik');
    }
};
