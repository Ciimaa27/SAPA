<?php

namespace App\Imports;

use App\Models\Wali;
use Maatwebsite\Excel\Concerns\ToModel;

class WaliImport implements ToModel
{
    public function model(array $row)
    {
        if ($row[0] == 'id_user') return null;

        return new Wali([
            'id_user'        => $row[0],
            'nama_wali'      => $row[1],
            'jenis_kelamin'  => !empty($row[2]) ? $row[2] : null,
            'fingerprint_id' => !empty($row[3]) ? $row[3] : null,
            'no_hp'          => !empty($row[4]) ? $row[4] : null,
        ]);
    }
}
