<?php

namespace App\Imports;

use App\Models\Guru;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;

class GuruImport implements ToModel
{
    public function model(array $row)
    {
        if ($row[0] == 'id_user') return null;

        $tanggal = $row[5] ?? null;
        if (!empty($tanggal) && is_numeric($tanggal)) {
            $tanggal = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal))->format('Y-m-d');
        } elseif (empty($tanggal) || $tanggal == '-') {
            $tanggal = null;
        }

        return new Guru([
            'id_user'       => $row[0],
            'nama_guru'     => $row[1],
            'nip'           => $row[2] ?? null,
            'no_hp'         => !empty($row[3]) ? $row[3] : null,
            'tempat_lahir'  => !empty($row[4]) ? $row[4] : null,
            'tanggal_lahir' => $tanggal,
        ]);
    }
}
