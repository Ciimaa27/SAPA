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
                DB::table('kehadiran')->insert([
                    'id_siswa' => $siswa->id_siswa,
                    'id_device' => 1,
                    'tanggal' => now()->toDateString(),
                    'jam_masuk' => now()->toTimeString(),
                    'metode' => 'rfid',
                    'status_hadir' => 'hadir'
                ]);

                echo "ABSEN: ".$siswa->nama_siswa."\n";
            } else {
                echo "UID tidak dikenal\n";
            }

        }, 0);

        $mqtt->loop(true);
    }
}