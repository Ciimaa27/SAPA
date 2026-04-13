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
        Schema::create('log_tap', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_device')->nullable();
            $table->string('uid_rfid')->nullable();
            $table->integer('fingerprint_id')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('status')->default('berhasil');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_tap');
    }
};
