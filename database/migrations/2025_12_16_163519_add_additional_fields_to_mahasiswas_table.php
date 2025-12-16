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
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('link_perusahaan')->nullable()->after('dosens');
            $table->string('dokumen_sks')->nullable()->after('link_perusahaan');
            $table->string('ttd_mahasiswa')->nullable()->after('dokumen_sks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn(['link_perusahaan', 'dokumen_sks', 'ttd_mahasiswa']);
        });
    }
};
