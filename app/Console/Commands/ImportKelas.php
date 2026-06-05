<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KelasImport;

class ImportKelas extends Command
{
    protected $signature = 'import:kelas';
    protected $description = 'Import kelas from Excel';

    public function handle()
    {
        Excel::import(new KelasImport, storage_path('app/kelas.xlsx'));
        $this->info('Import kelas berhasil!');
    }
}
