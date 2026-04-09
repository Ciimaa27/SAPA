<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wali extends Model
{
    protected $table = 'wali';    

    protected $primaryKey = 'id_wali';
    
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'nama_wali',
        'jenis_kelamin',
        'fingerprint_id',
        'no_wa',
        'no_hp',
        'is_active',
    ];
}