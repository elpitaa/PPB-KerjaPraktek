<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKp extends Model
{
    protected $table = 'laporan_kp';
    protected $fillable = [
        'id_pengajuan_kp',
        'judul',
        'status_laporan',
        'keterangan',
        'file',

    ];

    public function pengajuanKp()
    {
        return $this->belongsTo(Pengajuan_kp::class, 'id_pengajuan_kp');
    }

    public function ProposalKp()
    {
        return $this->hasOneThrough(
            ProposalKp::class,
            Pengajuan_kp::class,
            'id',
            'id_pengajuan_kp',
            'id_pengajuan_kp',
            'id'
        );
    }

    public function PenerimaanKp()
    {
        return $this->hasOneThrough(
            PenerimaanKP::class,
            Pengajuan_kp::class,
            'id',
            'id_pengajuan_kp',
            'id_pengajuan_kp',
            'id'
        );
    }

    public function mahasiswa()
    {
        return $this->hasOneThrough(
            Mahasiswa::class,
            Pengajuan_kp::class,
            'id',
            'id',
            'id_pengajuan_kp',
            'id_mahasiswa'
        );
    }
    public function Province()
    {
        return $this->hasOneThrough(
            Province::class,
            Pengajuan_kp::class,
            'id',
            'prov_id',
            'id_pengajuan_kp',
            'province_id'
        );
    }
    public function City()
    {
        return $this->hasOneThrough(
            City::class,
            Pengajuan_kp::class,
            'id',
            'city_id',
            'id_pengajuan_kp',
            'city_id'
        );
    }

    public function District()
    {
        return $this->hasOneThrough(
            District::class,
            Pengajuan_kp::class,
            'id',
            'dis_id',
            'id_pengajuan_kp',
            'district_id'
        );
    }

    public function SubDistrict()
    {
        return $this->hasOneThrough(
            SubDistrict::class,
            Pengajuan_kp::class,
            'id',
            'subdis_id',
            'id_pengajuan_kp',
            'subdistrict_id'
        );
    }

    public function PostalCode()
    {
        return $this->hasOneThrough(
            PostalCode::class,
            Pengajuan_kp::class,
            'id',
            'postal_id',
            'id_pengajuan_kp',
            'postal_code'
        );
    }
}
