<?php
require 'vendor/autoload.php';

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=db_sapa', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Available tables:\n";
    $stmt = $pdo->query("SHOW TABLES");
    while($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo "- {$row[0]}\n";
    }

    echo "\nChecking siswa table:\n";
    $stmt2 = $pdo->query('SELECT id_siswa, nama_siswa, id_kelas FROM siswa LIMIT 5');
    while($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: {$row['id_siswa']}, Nama: {$row['nama_siswa']}, Kelas: {$row['id_kelas']}\n";
    }

    echo "\nChecking kehadiran data for 2026-03-19:\n";
    $stmt3 = $pdo->query('SELECT * FROM kehadiran WHERE tanggal = "2026-03-19" LIMIT 10');
    $count = 0;
    while($row = $stmt3->fetch(PDO::FETCH_ASSOC)) {
        $count++;
        echo "ID: {$row['id_hadir']}, Siswa: {$row['id_siswa']}, Status: {$row['status_hadir']}, Keterangan: {$row['keterangan']}\n";
    }
    echo "Total records: $count\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}