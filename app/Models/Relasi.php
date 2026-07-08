<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relasi extends Model
{
    protected $table = 'siswa_wali';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id_siswa',
        'id_wali',
        'hubungan',
        'created_at',
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
