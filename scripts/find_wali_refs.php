<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

$ids = [314,404];
$output = [];
foreach ($ids as $id) {
    $output[$id] = [
        'siswa_wali' => DB::table('siswa_wali')->where('id_wali', $id)->get()->toArray(),
        'penjemputan' => DB::table('penjemputan')->where('id_wali', $id)->limit(10)->get()->toArray(),
        'notifikasi' => DB::table('notifikasi')->where('id_wali', $id)->limit(10)->get()->toArray(),
        'wali' => DB::table('wali')->where('id_wali', $id)->first()
    ];
}

echo json_encode($output, JSON_PRETTY_PRINT);
