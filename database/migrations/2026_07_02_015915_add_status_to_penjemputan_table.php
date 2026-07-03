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
    Schema::table('penjemputan', function (Blueprint $table) {

        $table->enum('status',[
            'Menunggu',
            'Dijemput'
        ])->default('Menunggu');

    });
}

public function down(): void
{
    Schema::table('penjemputan', function (Blueprint $table) {

        $table->dropColumn('status');

    });
}
};
