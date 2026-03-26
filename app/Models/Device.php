<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $table = 'iot_device';
    protected $primaryKey = 'id_device';
    public $timestamps = false;

    protected $fillable = [
        'nama_device',
        'status_koneksi'
    ];
}