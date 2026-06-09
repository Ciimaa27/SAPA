<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Kehadiran;
use Maatwebsite\Excel\Facades\Excel;

class ImportKehadiran extends Command
{
    /**
     * Nama command.
     *
     * php artisan import:kehadiran file.xlsx
     */
    protected $signature = 'import:kehadiran {file}';

    /**
     * Deskripsi command.
     */
    protected $description = 'Import data kehadiran dari file Excel';

    /**
     * Execute command.
     */
    public function handle()
    {
        $file = $this->argument('file');

        Excel::import(new \App\Imports\KehadiranImport, $file);

        $this->info('Data kehadiran berhasil diimport.');

        return Command::SUCCESS;
    }
}
