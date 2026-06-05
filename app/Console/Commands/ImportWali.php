<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\WaliImport;

class ImportWali extends Command
{
    protected $signature = 'import:wali';
    protected $description = 'Import wali from Excel';

    public function handle()
    {
        Excel::import(new WaliImport, storage_path('app/ortu.xlsx'));
        $this->info('Import wali berhasil!');
    }
}
