<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Wali;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'id_role',
        'username',
        'nama_lengkap',
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    // 🔥 WAJIB (biar auth pakai id_user)
    public function username()
    {
        return 'username';
    }

    // Relasi ke role
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    // Relasi ke wali
    public function wali()
    {
        return $this->hasOne(Wali::class, 'id_user', 'id_user');
    }
}
