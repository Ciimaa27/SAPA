<?php

namespace App\Imports;

use App\Models\Penjemputan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PenjemputanImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (
            empty($row['id_siswa']) ||
            empty($row['id_wali']) ||
            empty($row['tanggal']) ||
            empty($row['jam_jemput'])
        ) {
            return null;
        }

        return new Penjemputan([
            'id_siswa'   => (int) $row['id_siswa'],
            'id_wali'    => (int) $row['id_wali'],
            'tanggal'    => Date::excelToDateTimeObject($row['tanggal'])->format('Y-m-d'),
            'jam_jemput' => Date::excelToDateTimeObject($row['jam_jemput'])->format('H:i:s'),
        ]);
    }
}
