<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpMqtt\Client\MqttClient;
use Throwable;

class MQTTListen extends Command
{
    protected $signature = 'mqtt:listen';
    protected $description = 'Listen MQTT untuk RFID Siswa';

    public function handle()
    {
        $host = env('MQTT_HOST', '127.0.0.1');
        $port = (int) env('MQTT_PORT', 1883);
        $clientId = env('MQTT_CLIENT_ID', 'laravel-sapa');

        $mqtt = new MqttClient($host, $port, $clientId);

        try {
            $mqtt->connect();
            $this->info("MQTT Connected ke {$host}:{$port}");
        } catch (Throwable $e) {
            $this->error("Gagal koneksi MQTT: " . $e->getMessage());
            return Command::FAILURE;
        }

        $mqtt->subscribe('xsyau', function (string $topic, string $message) {

            echo "\n=====================================\n";
            echo "Topic   : {$topic}\n";
            echo "Payload : {$message}\n";

            $data = json_decode($message, true);

            if (!is_array($data) || !isset($data['uid'])) {
                echo "Data MQTT tidak valid.\n";
                return;
            }

            $uid = strtoupper(trim($data['uid']));

            echo "=====================================\n";
            echo "DEBUG RFID\n";
            echo "UID         : [{$uid}]\n";
            echo "Panjang UID : " . strlen($uid) . "\n";
            echo "HEX UID     : " . strtoupper(bin2hex($uid)) . "\n";
            echo "=====================================\n";

            // Tampilkan seluruh UID yang ada di database
            echo "\n===== DATA RFID DI DATABASE =====\n";

            $semuaSiswa = DB::table('siswa')
                ->select('id_siswa', 'nama_siswa', 'rfid_uid')
                ->get();

            foreach ($semuaSiswa as $row) {
                echo "{$row->id_siswa} | {$row->nama_siswa} | [{$row->rfid_uid}] | HEX: "
                    . strtoupper(bin2hex($row->rfid_uid)) . "\n";
            }

            echo "=====================================\n";

            // Cari siswa
            $siswa = DB::table('siswa')
                ->whereRaw('TRIM(UPPER(rfid_uid)) = ?', [$uid])
                ->first();

            // Simpan log scan
            DB::table('log_tap')->insert([
                'uid_rfid'   => $uid,
                'id_device'  => 1,
                'keterangan' => 'Scan RFID',
                'status'     => $siswa ? 'berhasil' : 'gagal',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (!$siswa) {
                echo "\nXXXXXXXX RFID TIDAK DITEMUKAN XXXXXXXX\n";
                echo "UID Dicari : {$uid}\n";
                echo "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX\n";
                return;
            }

            echo "\nSISWA DITEMUKAN\n";
            echo "Nama : {$siswa->nama_siswa}\n";
            echo "UID  : {$siswa->rfid_uid}\n";

            $today = now()->toDateString();

            // Cek apakah sudah absen hari ini
            $sudahAbsen = DB::table('kehadiran')
                ->where('id_siswa', $siswa->id_siswa)
                ->where('tanggal', $today)
                ->exists();

            if ($sudahAbsen) {
                echo "\n=====================================\n";
                echo "Siswa {$siswa->nama_siswa} sudah absen hari ini.\n";
                echo "=====================================\n";
                return;
            }

            // Simpan absensi
            DB::table('kehadiran')->insert([
                'id_siswa'      => $siswa->id_siswa,
                'id_device'     => 1,
                'tanggal'       => $today,
                'jam_masuk'     => now()->format('H:i:s'),
                'metode'        => 'rfid',
                'status_hadir'  => 'hadir',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            echo "\n=====================================\n";
            echo "ABSEN BERHASIL\n";
            echo "Nama : {$siswa->nama_siswa}\n";
            echo "UID  : {$uid}\n";
            echo "Jam  : " . now()->format('H:i:s') . "\n";
            echo "=====================================\n";

        }, 0);

        $this->info("Menunggu data RFID...");

        $mqtt->loop(true);

        return Command::SUCCESS;
    }
}