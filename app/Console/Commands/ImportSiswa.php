<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;

class ImportSiswa extends Command
{
    protected $signature = 'import:siswa';
    protected $description = 'Import siswa from Excel';

    public function handle()
    {
        Excel::import(new SiswaImport, storage_path('app/siswa.xlsx'));
        $this->info('Import siswa berhasil!');
    }
}
