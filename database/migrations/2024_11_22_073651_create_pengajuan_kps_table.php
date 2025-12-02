<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Teguh02\IndonesiaTerritoryForms\IndonesiaTerritoryForms;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_kp', function (Blueprint $table) {
            $table->id()->primary()->index('pengajuan_kp_id');
            $table->foreignId('id_mahasiswa')->constrained(table: 'mahasiswas', indexName: 'mahasiswa_id');
            $table->string('nama_perusahaan');
            $table->enum('status_pengajuan', ['pending', 'diterima', 'ditolak']);
            IndonesiaTerritoryForms::make_Columns($table);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_kps');
    }
};
