<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'provinces';
    protected $primaryKey = 'prov_id';
    protected $fillable = [
        'prov_id',
        'prov_code',
        'prov_name',
        'meta'
    ];
}
