<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\GuruImport;

class ImportGuru extends Command
{
    protected $signature = 'import:guru';
    protected $description = 'Import guru from Excel';

    public function handle()
    {
        Excel::import(new GuruImport, storage_path('app/guru.xlsx'));
        $this->info('Import guru berhasil!');
    }
}
