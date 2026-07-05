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
        $mqtt = new MqttClient(
            env('MQTT_HOST'),
            env('MQTT_PORT'),
            env('MQTT_CLIENT_ID')
        );

        $mqtt->connect();

        $this->info("========================================");
        $this->info(" MQTT CONNECTED");
        $this->info(" Topic : xsyau");
        $this->info(" Menunggu Scan RFID...");
        $this->info("========================================");

        $mqtt->subscribe('xsyau', function ($topic, $message) {

            $data = json_decode($message, true);

if (!$data || !isset($data['uid'])) {
    $this->error("Data MQTT tidak valid.");
    return;
}

// Abaikan pesan penjemputan fingerprint
if (
    isset($data['id_jari_wali']) ||
    (($data['status'] ?? null) === 'pulang')
) {
    return;
}

$uid = trim($data['uid']);

            if (!$data || !isset($data['uid'])) {
                $this->error("Data MQTT tidak valid.");
                return;
            }

            $uid = trim($data['uid']);

            $this->info("");
            $this->info("RFID Terdeteksi : " . $uid);

            $siswa = DB::table('siswa')
                ->leftJoin('kelas', 'siswa.id_kelas', '=', 'kelas.id_kelas')
                ->where('siswa.rfid_uid', $uid)
                ->select(
                    'siswa.*',
                    'kelas.nama_kelas'
                )
                ->first();

            if (!$siswa) {

                DB::table('log_tap')->insert([
                    'uid_rfid' => $uid,
                    'id_device' => 1,
                    'keterangan' => 'scan rfid',
                    'status' => 'gagal',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $this->error("========================================");
                $this->error(" RFID TIDAK TERDAFTAR");
                $this->error(" UID    : $uid");
                $this->error(" Status : GAGAL");
                $this->error("========================================");

                return;
            }

            $sudahAbsen = DB::table('kehadiran')
                ->where('id_siswa', $siswa->id_siswa)
                ->whereDate('tanggal', now()->toDateString())
                ->exists();

            if (!$sudahAbsen) {

                DB::table('kehadiran')->insert([
                    'id_siswa' => $siswa->id_siswa,
                    'id_device' => 1,
                    'tanggal' => now()->toDateString(),
                    'jam_masuk' => now()->toTimeString(),
                    'metode' => 'rfid',
                    'status_hadir' => 'hadir'
                ]);
            }

            DB::table('log_tap')->insert([
                'uid_rfid' => $uid,
                'id_device' => 1,
                'keterangan' => 'scan rfid',
                'status' => 'berhasil',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $this->info("========================================");
            $this->info(" ABSENSI SISWA BERHASIL");
            $this->info("========================================");
            $this->line("UID RFID   : " . $uid);
            $this->line("NIS        : " . $siswa->nis);
            $this->line("Nama       : " . $siswa->nama_siswa);
            $this->line("Kelas      : " . ($siswa->nama_kelas ?? '-'));
            $this->line("Tanggal    : " . now()->format('d-m-Y'));
            $this->line("Jam Masuk  : " . now()->format('H:i:s'));
            $this->line("Status     : HADIR");
            $this->info("========================================");
            $this->line("");

        }, 0);

        $mqtt->loop(true);

        $mqtt->disconnect();
    }
}
