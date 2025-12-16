<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostalCode extends Model
{
    protected $table = 'postal_code';
    protected $primaryKey = 'postal_id';
    protected $fillable = [
        'postal_id',
        'subdis_id',
        'dis_id',
        'city_id',
        'prov_id',
        'postal_code'
    ];
}
