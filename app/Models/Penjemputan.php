<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjemputan extends Model
{
    protected $table = 'penjemputan';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'id_siswa',
        'id_wali',
        'tanggal',
        'jam_jemput',
        "status_penjemputan",
        'keterangan',
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
