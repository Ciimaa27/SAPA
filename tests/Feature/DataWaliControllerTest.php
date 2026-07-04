<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Wali;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataWaliControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_view_includes_fingerprint_id(): void
    {
        $user = User::create([
            'id_role' => 1,
            'username' => 'admin2',
            'nama_lengkap' => 'Admin Test',
            'email' => 'admin2@test.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);

        Wali::create([
            'id_user' => $user->id,
            'nama_wali' => 'Wali Test',
            'jenis_kelamin' => 'laki-laki',
            'fingerprint_id' => 12345,
            'no_hp' => '081234567890',
            'is_active' => 1,
        ]);

        $response = $this->actingAs($user)->get('/admin/data-wali');

        $response->assertStatus(200);
        $response->assertViewHas('wali', function ($wali) {
            $first = $wali->first();
            return $first && $first->fingerprint_id === 12345;
        });
    }
}
