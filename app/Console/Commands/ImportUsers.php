<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

class ImportUsers extends Command
{
    protected $signature = 'import:users';
    protected $description = 'Import users from Excel';

    public function handle()
    {
        Excel::import(new UsersImport, storage_path('app/user.xlsx'));
        $this->info('Import users berhasil!');
    }
}
