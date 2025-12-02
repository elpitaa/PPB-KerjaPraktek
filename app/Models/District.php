<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'districts';
    protected $primaryKey = 'dis_id';
    protected $fillable = [
        'dis_id',
        'dis_code',
        'city_id',
        'dis_name',
        'meta'
    ];
}
