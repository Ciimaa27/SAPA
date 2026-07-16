<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relasi extends Model
{
    protected $table = 'siswa_wali';

    // Primary key tabel
    protected $primaryKey = 'id';

    // Karena id auto increment
    public $incrementing = true;

    // Tabel tidak menggunakan timestamps Laravel
    public $timestamps = false;

    protected $fillable = [
        'id_siswa',
        'id_wali',
        'hubungan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    public function wali()
    {
        return $this->belongsTo(Wali::class, 'id_wali');
    }
}
