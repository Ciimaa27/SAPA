<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$name = 'TEGUH HARSOYO PUTRO';
$phone = '087863303422';

$matches = DB::table('wali')
    ->select('id_wali','nama_wali','no_hp')
    ->where('nama_wali', $name)
    ->orWhere('no_hp', $phone)
    ->get()
    ->toArray();

$dup_names = DB::table('wali')
    ->select('nama_wali', DB::raw('COUNT(*) as cnt'))
    ->groupBy('nama_wali')
    ->havingRaw('COUNT(*)>1')
    ->get()
    ->toArray();

$dup_phones = DB::table('wali')
    ->select('no_hp', DB::raw('COUNT(*) as cnt'))
    ->groupBy('no_hp')
    ->havingRaw('COUNT(*)>1')
    ->get()
    ->toArray();

echo json_encode(['matches'=>$matches, 'dup_names'=>$dup_names, 'dup_phones'=>$dup_phones], JSON_PRETTY_PRINT);
