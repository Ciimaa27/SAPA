<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipSiswa extends Model
{
    protected $table = 'arsip_siswa';

    protected $primaryKey = 'id_arsip';

    protected $fillable = [
        'id_siswa_lama',
        'id_kelas',
        'nis',
        'nama_siswa',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'rfid_uid',
        'status',
        'tahun_lulus',
        'kelas_terakhir',
    ];
}