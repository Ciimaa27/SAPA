<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';

$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$id_kelas = 1;
$dates = ['2026-06-09','2026-06-10'];

$rows = DB::table('kehadiran')
    ->join('siswa','kehadiran.id_siswa','siswa.id_siswa')
    ->where('siswa.id_kelas',$id_kelas)
    ->whereIn('kehadiran.tanggal',$dates)
    ->select('kehadiran.*','siswa.nama_siswa','siswa.id_kelas')
    ->orderBy('kehadiran.tanggal')
    ->orderBy('kehadiran.jam_masuk')
    ->get();

if ($rows->isEmpty()) {
    echo "No rows found for class {$id_kelas} on dates: " . implode(', ', $dates) . "\n";
    exit(0);
}

foreach ($rows as $r) {
    echo "Date: {$r->tanggal} | Student: {$r->nama_siswa} | Status: {$r->status_hadir} | Jam masuk: {$r->jam_masuk} | Jam keluar: {$r->jam_keluar}\n";
}

echo "TOTAL: " . $rows->count() . "\n";
