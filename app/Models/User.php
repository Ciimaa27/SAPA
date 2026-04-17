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

    // login pakai username
    public function username()
    {
        return 'username';
    }

    // relasi role
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    // relasi wali
    public function wali()
    {
        return $this->hasOne(Wali::class, 'id_user', 'id'); 
    }
}
