<?php

namespace App\Imports;

use App\Models\Kehadiran;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class KehadiranImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Kehadiran([
            'id_siswa' => $row['id_siswa'],
            'id_device' => $row['id_device'],

            'tanggal' => Date::excelToDateTimeObject(
                $row['tanggal']
            )->format('Y-m-d'),

            'jam_masuk' => Date::excelToDateTimeObject(
                $row['jam_masuk']
            )->format('H:i:s'),

            'jam_keluar' => Date::excelToDateTimeObject(
                $row['jam_keluar']
            )->format('H:i:s'),

            'metode' => $row['metode'],
            'status_hadir' => $row['status_hadir'],
            'keterangan' => $row['keterangan'],
        ]);
    }
}
