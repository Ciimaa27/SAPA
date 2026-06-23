<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'id_user',
        'id_siswa',
        'id_wali',
        'judul',
        'pesan',
        'tipe',
        'status',
        'is_pushed',
        'tipe_notif',
        'status_wa',
    ];

    protected $casts = [
        'is_pushed' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    public function wali()
    {
        return $this->belongsTo(Wali::class, 'id_wali');
    }
}
