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
        // Tambah kolom surat_persetujuan di tabel pengajuan_kp
        Schema::table('pengajuan_kp', function (Blueprint $table) {
            if (!Schema::hasColumn('pengajuan_kp', 'surat_persetujuan')) {
                $table->string('surat_persetujuan')->nullable()->after('status_pengajuan');
            }
        });

        // Tambah kolom surat_persetujuan di tabel proposal_kps
        Schema::table('proposal_kps', function (Blueprint $table) {
            if (!Schema::hasColumn('proposal_kps', 'surat_persetujuan')) {
                $table->string('surat_persetujuan')->nullable()->after('status_proposal');
            }
        });

        // Tambah kolom surat_persetujuan di tabel laporan_kp
        Schema::table('laporan_kp', function (Blueprint $table) {
            if (!Schema::hasColumn('laporan_kp', 'surat_persetujuan')) {
                $table->string('surat_persetujuan')->nullable()->after('status_laporan');
            }
        });

        // Tambah kolom surat_selesai dan nilai di tabel sidang_kp
        Schema::table('sidang_kp', function (Blueprint $table) {
            if (!Schema::hasColumn('sidang_kp', 'surat_selesai')) {
                $table->string('surat_selesai')->nullable()->after('keterangan');
            }
            if (!Schema::hasColumn('sidang_kp', 'nilai')) {
                $table->decimal('nilai', 5, 2)->nullable()->after('keterangan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_kp', function (Blueprint $table) {
            $table->dropColumn('surat_persetujuan');
        });

        Schema::table('proposal_kps', function (Blueprint $table) {
            $table->dropColumn('surat_persetujuan');
        });

        Schema::table('laporan_kp', function (Blueprint $table) {
            $table->dropColumn('surat_persetujuan');
        });

        Schema::table('sidang_kp', function (Blueprint $table) {
            $table->dropColumn(['surat_selesai', 'nilai']);
        });
    }
};
