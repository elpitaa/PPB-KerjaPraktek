<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Mahasiswa extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nama tabel yang digunakan
     */
    protected $table = 'mahasiswas';

    /**
     * Kolom yang dapat diisi (fillable)
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nim',
        'ipk',
        'jumlah_sks',
        'dosens',     // foreign key ke dosen
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke model Dosen
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosens');
    }
}
