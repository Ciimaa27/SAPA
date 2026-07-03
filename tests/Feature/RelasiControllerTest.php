<?php

namespace Tests\Feature;

use App\Models\Relasi;
use App\Models\Siswa;
use App\Models\User;
use App\Models\Wali;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelasiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_form_only_shows_unassigned_siswa_and_wali(): void
    {
        $user = User::create([
            'id_role' => 1,
            'username' => 'admin1',
            'nama_lengkap' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);

        $assignedSiswa = Siswa::create([
            'nis' => '1001',
            'nama_siswa' => 'Siswa Terpakai',
            'jenis_kelamin' => 'Laki-laki',
            'is_active' => 1,
        ]);

        $unassignedSiswa = Siswa::create([
            'nis' => '1002',
            'nama_siswa' => 'Siswa Baru',
            'jenis_kelamin' => 'Perempuan',
            'is_active' => 1,
        ]);

        $assignedWali = Wali::create([
            'id_user' => $user->id,
            'nama_wali' => 'Wali Terpakai',
            'jenis_kelamin' => 'Laki-laki',
            'no_hp' => '081111111111',
            'is_active' => 1,
        ]);

        $unassignedWali = Wali::create([
            'id_user' => $user->id,
            'nama_wali' => 'Wali Baru',
            'jenis_kelamin' => 'Perempuan',
            'no_hp' => '082222222222',
            'is_active' => 1,
        ]);

        Relasi::create([
            'id_siswa' => $assignedSiswa->id_siswa,
            'id_wali' => $assignedWali->id_wali,
            'hubungan' => 'Ayah',
        ]);

        $response = $this->actingAs($user)->get('/admin/relasi/create');

        $response->assertStatus(200);
        $response->assertViewHas('siswa', function ($siswa) use ($unassignedSiswa, $assignedSiswa) {
            return $siswa->pluck('id_siswa')->all() === [$unassignedSiswa->id_siswa]
                && ! $siswa->contains('id_siswa', $assignedSiswa->id_siswa);
        });
        $response->assertViewHas('wali', function ($wali) use ($unassignedWali, $assignedWali) {
            return $wali->pluck('id_wali')->all() === [$unassignedWali->id_wali]
                && ! $wali->contains('id_wali', $assignedWali->id_wali);
        });
    }
}
