<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jadwal_pulang', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('kelas');
            $table->string('hari');
            $table->time('jam')->nullable();
            $table->boolean('libur')->default(false);
            $table->timestamps();

            $table->unique(['kelas', 'hari']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('jadwal_pulang');
    }
};
