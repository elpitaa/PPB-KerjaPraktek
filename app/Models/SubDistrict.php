<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubDistrict extends Model
{
    protected $table = 'villages';
    protected $primaryKey = 'subdis_id';
    protected $fillable = [
        'subdis_id',
        'subdis_code',
        'dis_id',
        'subdis_name',
        'meta'
    ];
}
