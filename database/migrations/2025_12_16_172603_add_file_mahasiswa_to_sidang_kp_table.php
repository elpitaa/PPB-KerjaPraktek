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
        Schema::table('sidang_kp', function (Blueprint $table) {
            $table->string('file_mahasiswa')->nullable()->after('surat_selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sidang_kp', function (Blueprint $table) {
            $table->dropColumn('file_mahasiswa');
        });
    }
};
