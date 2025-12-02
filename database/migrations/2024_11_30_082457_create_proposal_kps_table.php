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
        Schema::create('proposal_kps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengajuan_kp')->constrained('pengajuan_kp', 'id');
            $table->enum('status_proposal', ['pending', 'diterima', 'revisi']);
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
        Schema::dropIfExists('proposal_kps');
    }
};
