<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SapaSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // RESET
        DB::table('siswa_wali')->truncate();
        DB::table('penjemputan')->truncate();
        DB::table('kehadiran')->truncate();
        DB::table('notifikasi')->truncate();
        DB::table('log_tap')->truncate();
        DB::table('siswa')->truncate();
        DB::table('wali')->truncate();
        DB::table('kelas')->truncate();
        DB::table('guru')->truncate();
        DB::table('users')->truncate();
        DB::table('role')->truncate();
        DB::table('iot_device')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // =====================
        // ROLE
        // =====================
        DB::table('role')->insert([
            ['id_role'=>1,'nama_role'=>'Admin'],
            ['id_role'=>2,'nama_role'=>'Guru'],
            ['id_role'=>3,'nama_role'=>'Kepala Sekolah'],
            ['id_role'=>4,'nama_role'=>'Orang Tua/Wali'],
        ]);

        // =====================
        // USERS (AWAL)
        // =====================
        DB::table('users')->insert([
        [
            'id_role'=>1,
            'username'=>'admin',
            'nama_lengkap'=>'Admin Utama',
            'email'=>'admin@sapa.com',
            'password'=>bcrypt('123'),
            'status'=>'aktif',
            'created_at'=>now(),
            'updated_at'=>now(),
        ],
        [
            'id_role'=>2,
            'username'=>'guru1',
            'nama_lengkap'=>'Siti Aisyah',
            'email'=>'siti.aisyah@sapa.com',
            'password'=>bcrypt('123'),
            'status'=>'aktif',
            'created_at'=>now(),
            'updated_at'=>now(),
        ],
        [
            'id_role'=>2,
            'username'=>'guru2',
            'nama_lengkap'=>'Ahmad Fauzi',
            'email'=>'ahmad.fauzi@sapa.com',
            'password'=>bcrypt('123'),
            'status'=>'aktif',
            'created_at'=>now(),
            'updated_at'=>now(),
        ],
        [
            'id_role'=>3,
            'username'=>'kepsek',
            'nama_lengkap'=>'Kepala Sekolah',
            'email'=>'kepsek@sapa.com',
            'password'=>bcrypt('123'),
            'status'=>'aktif',
            'created_at'=>now(),
            'updated_at'=>now(),
        ],
    ]);

        // =====================
        // GURU (AMBIL ID USER)
        // =====================
        $guru1 = DB::table('users')->where('username','guru1')->value('id');
        $guru2 = DB::table('users')->where('username','guru2')->value('id');

        DB::table('guru')->insert([
            [
                'id_guru'=>1,
                'id_user'=>$guru1,
                'nama_guru'=>'Siti Aisyah',
                'nip'=>'19800101',
                'no_hp'=>'081234567001',
                'tempat_lahir'=>'Banjarmasin',
                'tanggal_lahir'=>'1980-01-01'
            ],
            [
                'id_guru'=>2,
                'id_user'=>$guru2,
                'nama_guru'=>'Ahmad Fauzi',
                'nip'=>'19800102',
                'no_hp'=>'081234567002',
                'tempat_lahir'=>'Banjarbaru',
                'tanggal_lahir'=>'1980-02-02'
            ],
        ]);

        // =====================
        // KELAS
        // =====================
        for ($i=1; $i<=6; $i++) {
            DB::table('kelas')->insert([
                'id_kelas'=>$i,
                'nama_kelas'=>'Kelas '.$i,
                'id_guru'=>($i<=3)?1:2
            ]);
        }

        // =====================
        // SISWA
        // =====================
        $namaDepan = ['Ahmad','Budi','Citra','Dewi','Eka','Fajar','Gita','Hadi','Indah','Joko'];
        $namaBelakang = ['Saputra','Pratama','Lestari','Wijaya','Putri','Santoso','Sari','Utama'];
        $tempat = ['Banjarmasin','Banjarbaru','Palangkaraya','Samarinda','Balikpapan'];

        for ($i=1; $i<=50; $i++) {

        $nama = $namaDepan[array_rand($namaDepan)].' '.$namaBelakang[array_rand($namaBelakang)];
        $kelas = rand(1,6);

        DB::table('siswa')->insert([
            'id_siswa'=>$i,
            'nis'=>1000+$i,
            'id_kelas'=>$kelas, 
            'nama_siswa'=>$nama,
            'jenis_kelamin'=>($i%2==0)?'Perempuan':'Laki-laki',
            'tempat_lahir'=>$tempat[array_rand($tempat)],
            'tanggal_lahir' => now()->subYears(6 + $kelas)->toDateString(),
            'rfid_uid'=>'RFID'.str_pad($i,3,'0',STR_PAD_LEFT),
            'status'=>'aktif',
            'is_active'=>1
        ]);
    }

        // =====================
        // WALI + USER (DIGABUNG)
        // =====================
        $namaDepan = ['Budi','Siti','Andi','Rina','Agus','Dewi','Rudi','Lina','Hendra','Maya'];
        $namaBelakang = ['Santoso','Wijaya','Saputra','Putri','Pratama','Lestari','Utama','Sari'];

        for ($i=1; $i<=50; $i++) {

            $nama = $namaDepan[array_rand($namaDepan)].' '.$namaBelakang[array_rand($namaBelakang)];
            $username = 'wali'.$i;
            $email = strtolower(str_replace(' ','',$nama)).$i.'@gmail.com';

            $userId = DB::table('users')->insertGetId([
                'id_role'=>4,
                'username'=>$username,
                'nama_lengkap'=>$nama,
                'email'=>$email,
                'password'=>bcrypt('123'),
                'status'=>'aktif',
                'created_at'=>now(),
                'updated_at'=>now(),
            ]);

            DB::table('wali')->insert([
                'id_wali'=>$i,
                'id_user'=>$userId,
                'nama_wali'=>$nama,
                'jenis_kelamin'=>($i%2==0)?'Perempuan':'Laki-laki',
                'fingerprint_id'=>$i,
                'no_hp'=>'08'.rand(11,99).rand(10000000,99999999),
                'is_active'=>1
            ]);
        }

        // =====================
        // RELASI SISWA-WALI
        // =====================
        for ($i=1; $i<=50; $i++) {
            DB::table('siswa_wali')->insert([
                'id_siswa'=>$i,
                'id_wali'=>$i,
                'hubungan'=>($i%2==0)?'ibu':'ayah'
            ]);
        }

        // =====================
        // DEVICE
        // =====================
        DB::table('iot_device')->insert([
            ['id_device'=>1,'nama_device'=>'RFID','status_koneksi'=>'online'],
            ['id_device'=>2,'nama_device'=>'Fingerprint','status_koneksi'=>'online']
        ]);

        // =====================
        // KEHADIRAN
        // =====================
        $statuses = ['hadir','sakit','izin','alpa'];

        for ($i=1; $i<=50; $i++) {
            for ($d=0; $d<30; $d++) {

                $status = $statuses[array_rand($statuses)];

                DB::table('kehadiran')->insert([
                    'id_siswa'=>$i,
                    'id_device'=>1,
                    'tanggal'=>now()->subDays($d)->toDateString(),
                    'jam_masuk' => $status=='hadir' ? rand(6,8).':'.rand(0,59).':00' : null,
                    'jam_keluar' => $status=='hadir' ? rand(11,13).':'.rand(0,59).':00' : null,
                    'metode'=>'rfid',
                    'status_hadir'=>$status,
                    'keterangan'=>ucfirst($status)
                ]);
            }
        }

        // =====================
        // PENJEMPUTAN
        // =====================
        for ($i=1; $i<=50; $i++) {
            for ($d=0; $d<10; $d++) {

                DB::table('penjemputan')->insert([
                    'id_siswa'=>$i,
                    'id_wali'=>$i,
                    'tanggal'=>now()->subDays($d)->toDateString(),
                    'jam_jemput'=>'12:0'.rand(0,5).':00'
                ]);
            }
        }

        // =====================
        // NOTIFIKASI
        // =====================
        for ($i=1; $i<=50; $i++) {

            $userId = DB::table('wali')->where('id_wali',$i)->value('id_user');

            DB::table('notifikasi')->insert([
                'id_user'=>$userId,
                'id_siswa'=>$i,
                'id_wali'=>$i,
                'judul'=>'Penjemputan Siswa',
                'pesan'=>'Siswa telah dijemput',
                'tipe'=>'penjemputan',
                'status'=>'belum_dibaca',
                'is_pushed'=>1,
                'created_at'=>now(),
                'tipe_notif'=>'wa',
                'status_wa'=>'sent'
            ]);
        }

        // =====================
        // LOG TAP
        // =====================
        for ($i=1; $i<=50; $i++) {

            DB::table('log_tap')->insert([
                'id_device'=>1,
                'uid_rfid'=>'RFID'.str_pad($i,3,'0',STR_PAD_LEFT),
                'keterangan'=>'Absensi siswa',
                'created_at'=>now()->subMinutes(rand(1,500)),
                'status'=>'berhasil'
            ]);

            DB::table('log_tap')->insert([
                'id_device'=>2,
                'fingerprint_id'=>$i,
                'keterangan'=>'Penjemputan wali',
                'created_at'=>now()->subMinutes(rand(1,500)),
                'status'=>'berhasil'
            ]);
        }
    }
}