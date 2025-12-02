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
        Schema::create('laporan_kp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan_kp')->constrained('pengajuan_kp');
            $table->string('judul');
            $table->enum('status_laporan', ['diterima', 'ditolak', 'revisi', 'pending']);
            $table->string('keterangan');
            $table->string('file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kp');
    }
};
