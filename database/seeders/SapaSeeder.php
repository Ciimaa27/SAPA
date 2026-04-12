<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SapaSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('role')->truncate();
        DB::table('users')->truncate();
        DB::table('guru')->truncate();
        DB::table('kelas')->truncate();
        DB::table('siswa')->truncate();
        DB::table('wali')->truncate();
        DB::table('siswa_wali')->truncate();
        DB::table('penjemputan')->truncate();
        DB::table('kehadiran')->truncate();

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
        // USERS
        // =====================
        DB::table('users')->insert([
            ['id'=>1,'id_role'=>1,'username'=>'admin','nama_lengkap'=>'Admin','email'=>'admin@mail.com','password'=>bcrypt('123'),'status'=>'aktif'],
            ['id'=>2,'id_role'=>2,'username'=>'guru1','nama_lengkap'=>'Siti Aisyah','email'=>'guru1@mail.com','password'=>bcrypt('123'),'status'=>'aktif'],
            ['id'=>3,'id_role'=>2,'username'=>'guru2','nama_lengkap'=>'Ahmad Fauzi','email'=>'guru2@mail.com','password'=>bcrypt('123'),'status'=>'aktif'],
            ['id'=>4,'id_role'=>3,'username'=>'kepsek','nama_lengkap'=>'Kepala Sekolah','email'=>'kepsek@mail.com','password'=>bcrypt('123'),'status'=>'aktif'],
        ]);

        // =====================
        // GURU
        // =====================
        DB::table('guru')->insert([
            [
                'id_guru'=>1,
                'id_user'=>2,
                'nama_guru'=>'Siti Aisyah',
                'nip'=>'19800101',
                'no_hp'=>'08123456789',
                'tempat_lahir'=>'Banjarmasin',
                'tanggal_lahir'=>'1980-01-01'
            ],
            [
                'id_guru'=>2,
                'id_user'=>3,
                'nama_guru'=>'Ahmad Fauzi',
                'nip'=>'19800202',
                'no_hp'=>'08234567890',
                'tempat_lahir'=>'Banjarbaru',
                'tanggal_lahir'=>'1982-02-02'
            ],
        ]);

        // =====================
        // KELAS
        // =====================
        DB::table('kelas')->insert([
            ['id_kelas'=>1,'nama_kelas'=>'1-A','id_guru'=>1],
            ['id_kelas'=>2,'nama_kelas'=>'1-B','id_guru'=>2],
            ['id_kelas'=>3,'nama_kelas'=>'2-A','id_guru'=>1],
            ['id_kelas'=>4,'nama_kelas'=>'2-B','id_guru'=>2],
            ['id_kelas'=>5,'nama_kelas'=>'3-A','id_guru'=>1],
            ['id_kelas'=>6,'nama_kelas'=>'3-B','id_guru'=>2],
        ]);

        // =====================
        // SISWA (50)
        // =====================
        for ($i=1; $i<=50; $i++) {
            DB::table('siswa')->insert([
                'id_siswa' => $i,
                'nis' => 1000 + $i,
                'id_kelas' => rand(1,6),
                'nama_siswa' => 'Siswa '.$i,
                'jenis_kelamin' => rand(0,1) ? 'Laki-laki' : 'Perempuan',
                'tempat_lahir' => 'Banjarmasin',
                'tanggal_lahir' => date('Y-m-d', strtotime('-'.rand(7,12).' years')),
                'rfid_uid' => 'RFID'.str_pad($i,3,'0',STR_PAD_LEFT),
                'status' => 'aktif',
                'is_active' => 1
            ]);
        }

        // =====================
        // WALI (50)
        // =====================
        for ($i=1; $i<=50; $i++) {

            DB::table('users')->insert([
                'id' => 100+$i,
                'id_role' => 4,
                'username' => 'wali'.$i,
                'nama_lengkap' => 'Wali '.$i,
                'email' => 'wali'.$i.'@mail.com',
                'password' => bcrypt('123'),
                'status' => 'aktif'
            ]);

            DB::table('wali')->insert([
                'id_wali' => $i,
                'id_user' => 100+$i,
                'nama_wali' => 'Wali '.$i,
                'jenis_kelamin' => rand(0,1) ? 'Laki-laki' : 'Perempuan',
                'no_hp' => '08123'.rand(100000,999999),
                'is_active' => 1
            ]);
        }

        // =====================
        // RELASI SISWA - WALI
        // =====================
        for ($i=1; $i<=50; $i++) {
            DB::table('siswa_wali')->insert([
                'id_siswa'=>$i,
                'id_wali'=>$i,
                'hubungan'=> rand(0,1) ? 'ayah' : 'ibu'
            ]);
        }

        // =====================
        // PENJEMPUTAN
        // =====================
        for ($i=1; $i<=50; $i++) {
            DB::table('penjemputan')->insert([
                'id_siswa'=>$i,
                'id_wali'=>$i,
                'tanggal'=>date('Y-m-d'),
                'jam_jemput'=>'12:00:00',
                'status'=>'dijemput'
            ]);
        }

        // =====================
        // KEHADIRAN (1500 DATA)
        // =====================
        for ($hari = 1; $hari <= 30; $hari++) {
            for ($i = 1; $i <= 50; $i++) {

                $tanggal = date('Y-m-d', strtotime("-$hari days"));

                DB::table('kehadiran')->insert([
                    'id_siswa' => $i,
                    'id_device' => 1,
                    'tanggal' => $tanggal,
                    'jam_masuk' => rand(0,1) ? '07:0'.rand(0,9).':00' : null,
                    'jam_keluar' => rand(0,1) ? '12:0'.rand(0,9).':00' : null,
                    'metode' => 'rfid',
                    'status_hadir' => collect(['hadir','izin','sakit','alpa'])->random(),
                    'keterangan' => collect(['Tepat waktu','Terlambat','Izin','Sakit'])->random(),
                ]);
            }
        }
    }
}