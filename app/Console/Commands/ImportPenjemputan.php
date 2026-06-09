<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PenjemputanImport;

class ImportPenjemputan extends Command
{
    protected $signature = 'import:penjemputan {file}';

    protected $description = 'Import data penjemputan dari file excel';

    public function handle()
    {
        $file = $this->argument('file');

        Excel::import(new PenjemputanImport, $file);

        $this->info('Data penjemputan berhasil diimport');

        return Command::SUCCESS;
    }
}
