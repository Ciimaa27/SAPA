<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_siswa')->nullable();
            $table->unsignedBigInteger('id_wali')->nullable();

            $table->string('judul');
            $table->text('pesan');
            $table->string('tipe');
            $table->string('status')->default('belum_dibaca');

            $table->boolean('is_pushed')->default(false);
            $table->string('tipe_notif')->nullable();
            $table->string('status_wa')->nullable();

            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
