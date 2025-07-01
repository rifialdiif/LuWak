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
        Schema::create('user', function (Blueprint $table) {
            $table->bigIncrements('id_user');
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('password_hash');
            $table->string('role', 20);
            $table->string('nip_nim', 30)->nullable();
            $table->unsignedBigInteger('id_prodi')->nullable();

            $table->foreign('id_prodi')->references('id_prodi')->on('prodi')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
};
