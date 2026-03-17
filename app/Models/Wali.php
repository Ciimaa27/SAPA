<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wali extends Model
{
    protected $table = 'wali';       // nama tabel sesuai database
    protected $primaryKey = 'id_wali';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'nama_wali',
        'no_wa',
        'fingerprint_id',
        'no_hp',
        'is_active',
    ];
}