<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPulang extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pulang';

    protected $fillable = [
        'kelas',
        'hari',
        'jam',
        'libur',
    ];

    protected $casts = [
        'kelas' => 'integer',
        'libur' => 'boolean',
    ];
}
