<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Penilaian;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenilaianClassDisplayTest extends TestCase
{
    use RefreshDatabase;

    public function test_class_is_displayed_in_penilaian_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $ta = TahunAjaran::create([
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);

        $guruUser = User::factory()->create(['role' => 'guru']);
        $guru = Guru::create([
            'nuptk' => '1',
            'nama' => 'Guru',
            'email' => 'guru@example.com',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
            'user_id' => $guruUser->id,
        ]);

        $kelas = Kelas::create([
            'nama' => 'X-1',
            'guru_id' => $guru->id,
            'tahun_ajaran_id' => $ta->id,
        ]);

        $siswaUser = User::factory()->create(['role' => 'siswa']);
        $siswa = Siswa::create([
            'nama' => 'Siswa',
            'nisn' => '123',
            'nama_ortu' => 'Orang Tua',
            'kelas' => $kelas->nama,
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2005-01-01',
            'user_id' => $siswaUser->id,
            'tahun_ajaran_id' => $ta->id,
        ]);

        $mapel = MataPelajaran::create(['nama' => 'IPA']);

        Penilaian::create([
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'semester' => 1,
            'hadir' => 10,
            'sakit' => 0,
            'izin' => 0,
            'alpha' => 0,
        ]);

        $response = $this->actingAs($admin)->get('/penilaian');

        $response->assertOk();
        $response->assertSee('Kelas');
        $response->assertSee($kelas->nama);
    }
}
