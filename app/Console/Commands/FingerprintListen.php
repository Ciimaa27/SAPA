<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use Illuminate\Support\Facades\DB;
use Throwable;

class FingerprintListen extends Command
{
    protected $signature = 'fingerprint:listen';
    protected $description = 'Listen MQTT Fingerprint Wali';

    public function handle()
    {
        $mqtt = new MqttClient(
            env('MQTT_HOST'),
            env('MQTT_PORT'),
            'laravel-fingerprint'
        );

        $mqtt->connect();

        $this->info("========================================");
        $this->info(" MQTT CONNECTED");
        $this->info(" Topic : xsyau");
        $this->info(" Menunggu Scan Fingerprint...");
        $this->info("========================================");

        $mqtt->subscribe('xsyau', function ($topic, $message) {
            // Memastikan koneksi DB tetap segar saat ada data masuk
            DB::reconnect();

            $data = json_decode($message, true);

            // Validasi format JSON
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
                $this->error("Format pesan MQTT tidak valid (Bukan JSON).");
                return;
            }

            // hanya proses jika status = pulang
            if (!isset($data['status']) || $data['status'] != 'pulang') {
                return;
            }

            if (!isset($data['uid']) || !isset($data['id_jari_wali'])) {
                $this->error("Data fingerprint tidak lengkap.");
                return;
            }

            $uid = strtoupper($data['uid']);
            $fingerprintId = $data['id_jari_wali'];

            // Tampilkan data hasil scan terlebih dahulu
            $this->info("");
            $this->info("========================================");
            $this->info(" FINGERPRINT TERDETEKSI");
            $this->info("========================================");
            $this->line("UID RFID       : ".$uid);
            $this->line("ID Fingerprint : ".$fingerprintId);
            $this->line("Status         : ".$data['status']);
            $this->info("========================================");

            $siswa = DB::table('siswa')
                ->leftJoin('kelas','kelas.id_kelas','=','siswa.id_kelas')
                ->where('rfid_uid',$uid)
                ->select('siswa.*', 'kelas.nama_kelas')
                ->first();

            if(!$siswa){
                $this->error("UID RFID belum terdaftar.");
                return;
            }

            $penjemput = DB::table('wali')
                ->join('siswa_wali', 'wali.id_wali', '=', 'siswa_wali.id_wali')
                ->where('siswa_wali.id_siswa', $siswa->id_siswa)
                ->where('wali.fingerprint_id', $fingerprintId)
                ->select(
                    'wali.id_wali',
                    'wali.nama_wali',
                    'wali.fingerprint_id',
                    'siswa_wali.id_siswa', 
                    'siswa_wali.hubungan'
                )
                ->first();

            if (!$penjemput) {
                $this->error("========================================");
                $this->error(" FINGERPRINT BELUM TERDAFTAR / TIDAK COCOK");
                $this->error(" UID RFID       : ".$uid);
                $this->error(" ID Fingerprint : ".$fingerprintId);
                $this->error(" Status         : GAGAL");
                $this->error("========================================");
                return;
            }

            try {
                // Cek apakah sudah dijemput hari ini
                $sudahJemput = DB::table('penjemputan')
                    ->where('id_siswa', $penjemput->id_siswa)
                    ->whereDate('tanggal', now()->toDateString())
                    ->exists();

                if (!$sudahJemput) {
                    DB::table('penjemputan')->insert([
                        'id_siswa'   => $penjemput->id_siswa,
                        'id_wali'    => $penjemput->id_wali,
                        'tanggal'    => now()->toDateString(),
                        'jam_jemput' => now()->toTimeString(),
                    ]);
                }

                $this->info("");
                $this->info("========================================");
                $this->info("      PENJEMPUTAN BERHASIL");
                $this->info("========================================");
                $this->line("ID Jari     : ".$fingerprintId);
                $this->line("Nama Wali   : ".$penjemput->nama_wali);
                $this->line("Hubungan    : ".$penjemput->hubungan);
                $this->line("");
                $this->line("NIS         : ".$siswa->nis);
                $this->line("Nama Siswa  : ".$siswa->nama_siswa);
                $this->line("Kelas       : ".$siswa->nama_kelas);
                $this->line("");
                $this->line("Tanggal     : ".now()->format('d-m-Y'));
                $this->line("Jam Jemput  : ".now()->format('H:i:s'));
                $this->line("Status      : ".($sudahJemput ? "SUDAH ABSEN SEBELUMNYA" : "BERHASIL"));
                $this->info("========================================");
                $this->line("");

            } catch (Throwable $e) {
                $this->error("Gagal menyimpan data ke database: " . $e->getMessage());
            }

        }, 0);

        // Loop selamanya selama koneksi aktif
        while ($mqtt->isConnected()) {
            $mqtt->loop(true);
            usleep(100000); // 100ms agar CPU tidak 100%
        }
    }
}