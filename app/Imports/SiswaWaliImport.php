<?php

namespace App\Imports;

use App\Models\Relasi;
use Maatwebsite\Excel\Concerns\ToModel;

class SiswaWaliImport implements ToModel
{
    public function model(array $row)
    {
        if ($row[0] == 'id_siswa') return null;

        return new Relasi([
            'id_siswa'  => $row[0],
            'id_wali'   => $row[1],
            'hubungan'  => $row[2],
        ]);
    }
}
