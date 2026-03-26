<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    protected $table = 'kehadiran';

    protected $primaryKey = 'id_hadir';

    public $timestamps = false;

    protected $fillable = [
        'id_siswa',
        'id_device',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'metode',
        'status_hadir',
        'keterangan',
    ];

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
}