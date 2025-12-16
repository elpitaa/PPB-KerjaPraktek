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
        Schema::create('sidang_kp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan_kp')->constrained('pengajuan_kp');
            $table->string('keterangan');
            $table->dateTime('tanggal')->nullable();

            $table->string('ruangan')->nullable();
            $table->string('penguji')->nullable();
            $table->string('nilai')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sidang_kp');
    }
};
