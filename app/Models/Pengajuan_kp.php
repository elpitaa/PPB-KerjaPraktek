<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan_kp extends Model
{
    protected $table = 'pengajuan_kp';
    protected $fillable = [
        'id_mahasiswa',
        'nama_perusahaan',
        'status_pengajuan',
        'province_id',
        'city_id',
        'district_id',
        'subdistrict_id',
        'postal_code',
        'created_at',
        'updated_at',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
    public function subdistrict()
    {
        return $this->belongsTo(SubDistrict::class, 'subdistrict_id');
    }
    public function postalcode()
    {
        return $this->belongsTo(PostalCode::class, 'postal_code');
    }

    public function proposalKp()
    {
        return $this->hasOne(ProposalKp::class, 'id_pengajuan_kp');
    }

    public function penerimaanKp()
    {
        return $this->hasOne(PenerimaanKP::class, 'id_pengajuan_kp');
    }

    public function laporanKp()
    {
        return $this->hasOne(LaporanKp::class, 'id_pengajuan_kp');
    }
    public function sidangKp()
    {
        return $this->hasOne(SidangKp::class, 'id_pengajuan_kp');
    }
}
