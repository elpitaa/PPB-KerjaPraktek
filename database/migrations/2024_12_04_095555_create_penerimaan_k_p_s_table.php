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
        Schema::create('penerimaan_kp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan_kp')->constrained('pengajuan_kp');
            $table->enum('status_penerimaan', ['diterima', 'ditolak']);
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
        Schema::dropIfExists('penerimaan_kp');
    }
};
