<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use Illuminate\Support\Facades\DB;

class MQTTListen extends Command
{
    protected $signature = 'mqtt:listen';
    protected $description = 'Listen MQTT RFID';

    public function handle()
    {
        $mqtt = new MqttClient('127.0.0.1', 1883, 'laravel-sapa');
        $mqtt->connect();

        $this->info("MQTT Connected...");

        $mqtt->subscribe('sapa/rfid', function ($topic, $message) {

            $data = json_decode($message, true);

            if (!isset($data['uid'])) {
                echo "Data tidak valid\n";
                return;
            }

            $uid = $data['uid'];

            echo "UID: $uid\n";

            $siswa = DB::table('siswa')
                ->where('rfid_uid', $uid)
                ->first();

            if ($siswa) {

                // ✅ simpan ke kehadiran (ABSEN)
                DB::table('kehadiran')->insert([
                    'id_siswa' => $siswa->id_siswa,
                    'id_device' => 1,
                    'tanggal' => now()->toDateString(),
                    'jam_masuk' => now()->toTimeString(),
                    'metode' => 'rfid',
                    'status_hadir' => 'hadir'
                ]);

                // 🔥 TAMBAHAN (INI YANG PENTING)
                DB::table('log_tap')->insert([
                    'uid_rfid' => $uid,
                    'id_device' => 1,
                    'keterangan' => 'scan rfid',
                    'status' => 'berhasil',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                echo "ABSEN: ".$siswa->nama_siswa."\n";
            }

        }, 0);

        $mqtt->loop(true);
    }
}