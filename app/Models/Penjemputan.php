<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjemputan extends Model
{
    protected $table = 'penjemputan';

    protected $primaryKey = 'id_penjemputan';

    public $timestamps = false;

    protected $fillable = [
        'id_siswa',
        'tanggal',
        'status',
        'jam_jemput',
        'keterangan'
    ];

    // relasi ke siswa (opsional tapi bagus)
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
}