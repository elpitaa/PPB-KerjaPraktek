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
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id()->primary()->index('mahasiswa_id');
            $table->string('name');
            $table->char('nim', 12)->unique();
            $table->string('email')->unique();
            $table->float('ipk', 3, 2);
            $table->integer('jumlah_sks');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('dosens')->constrained(table: 'dosens', indexName: 'dosen_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
