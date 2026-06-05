<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaWaliImport;

class ImportSiswaWali extends Command
{
    protected $signature = 'import:siswa-wali';
    protected $description = 'Import siswa wali from Excel';

    public function handle()
    {
        Excel::import(new SiswaWaliImport, storage_path('app/siswa_wali.xlsx'));
        $this->info('Import siswa wali berhasil!');
    }
}
