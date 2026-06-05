<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arsip_siswa', function (Blueprint $table) {

            $table->id('id_arsip');

            $table->unsignedBigInteger('id_siswa_lama')->nullable();

            $table->unsignedBigInteger('id_kelas')->nullable();

            $table->string('nis')->nullable();
            $table->string('nama_siswa');

            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();

            $table->string('jenis_kelamin')->nullable();

            $table->string('rfid_uid')->nullable();

            $table->string('status')->default('lulus');

            $table->year('tahun_lulus');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsip_siswa');
    }
};