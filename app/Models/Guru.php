<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru'; // nama tabel

    protected $primaryKey = 'id_guru'; // PK bukan id

    public $timestamps = false; // kalau tabel tidak pakai created_at

    protected $fillable = [
        'id_user',
        'nama_guru',
        'nip',
        'no_hp',
        'tempat_lahir',
        'tanggal_lahir'
    ];
}