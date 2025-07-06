<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Pengajaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengajaranUniqueTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_assign_two_teachers_to_same_subject_and_class(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $guruA = Guru::create([
            'nuptk' => '1001',
            'nama' => 'Guru A',
            'tempat_lahir' => 'Bandung',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
        ]);
        $guruB = Guru::create([
            'nuptk' => '1002',
            'nama' => 'Guru B',
            'tempat_lahir' => 'Jakarta',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1985-01-01',
        ]);
        $mapel = MataPelajaran::create(['nama' => 'Matematika']);
        $wali = Guru::create([
            'nuptk' => '1003',
            'nama' => 'Wali 10',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
        ]);
        $kelas = Kelas::create(['nama' => '10', 'guru_id' => $wali->id]);

        $this->actingAs($user)->post('/pengajaran', [
            'guru_nama' => $guruA->nama,
            'mapel_id' => $mapel->id,
            'kelas' => $kelas->nama,
        ])->assertRedirect('/pengajaran');

        $response = $this->actingAs($user)
            ->from('/pengajaran/create')
            ->post('/pengajaran', [
                'guru_nama' => $guruB->nama,
                'mapel_id' => $mapel->id,
                'kelas' => $kelas->nama,
            ]);

        $response->assertRedirect('/pengajaran/create');
        $response->assertSessionHas('error');
        $this->assertEquals(1, Pengajaran::count());
    }
}
