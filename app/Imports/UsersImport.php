<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    public function model(array $row)
    {
        if ($row[0] == 'id_role') return null;

        return User::updateOrCreate(
            ['username' => $row[1]], // cari berdasarkan username
            [
                'id_role'      => $row[0],
                'username'     => $row[1],
                'nama_lengkap' => $row[2],
                'email'        => $row[3],
                'password'     => Hash::make($row[4]),
                'status'       => $row[5],
            ]
        );
    }
}
