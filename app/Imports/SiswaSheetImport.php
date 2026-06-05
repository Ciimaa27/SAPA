<?php

namespace App\Imports;

use App\Models\Siswa;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;

class SiswaSheetImport implements ToModel
{
    protected $id_kelas;

    public function __construct($id_kelas)
    {
        $this->id_kelas = $id_kelas;
    }

    public function model(array $row)
    {
        if ($row[0] == 'id_kelas') return null;

        $tanggal = $row[4] ?? null;
        if (!empty($tanggal) && is_numeric($tanggal)) {
            $tanggal = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal))->format('Y-m-d');
        } elseif (empty($tanggal)) {
            $tanggal = null;
        }

        return new Siswa([
            'id_kelas'      => $row[0],
            'nis'           => !empty($row[1]) ? $row[1] : null,
            'nama_siswa'    => $row[2],
            'tempat_lahir'  => !empty($row[3]) ? $row[3] : null,
            'tanggal_lahir' => $tanggal,
            'jenis_kelamin' => !empty($row[5]) ? $row[5] : null,
            'rfid_uid'      => !empty($row[6]) ? $row[6] : null,
            'status'        => 'aktif',
        ]);
    }
}
