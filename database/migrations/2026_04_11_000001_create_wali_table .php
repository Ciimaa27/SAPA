<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wali', function (Blueprint $table) {
            $table->id('id_wali');

            $table->unsignedBigInteger('id_user');
            $table->string('nama_wali');
            $table->string('jenis_kelamin')->nullable();
            $table->integer('fingerprint_id')->nullable();
            $table->string('no_hp')->nullable();
            $table->boolean('is_active')->default(1);

            $table->timestamps();

            $table->foreign('id_user')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wali');
    }
};