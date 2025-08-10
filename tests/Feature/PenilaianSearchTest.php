<?php

namespace Tests\Feature;

use App\Models\MataPelajaran;
use App\Models\Penilaian;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenilaianSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_penilaian_can_be_searched_by_name_class_and_subject(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $ta = TahunAjaran::create([
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);

        $guru1 = Guru::create([
            'nuptk' => '1',
            'nama' => 'Guru 1',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
        ]);
        $guru2 = Guru::create([
            'nuptk' => '2',
            'nama' => 'Guru 2',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
        ]);

        Kelas::create(['nama' => 'X', 'guru_id' => $guru1->id, 'tahun_ajaran_id' => $ta->id]);
        Kelas::create(['nama' => 'XI', 'guru_id' => $guru2->id, 'tahun_ajaran_id' => $ta->id]);

        $siswa1 = Siswa::create([
            'nama' => 'Budi',
            'nisn' => '111',
            'nama_ortu' => 'Orang Tua',
            'kelas' => 'X',
            'tahun_ajaran_id' => $ta->id,
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
        ]);
        $siswa2 = Siswa::create([
            'nama' => 'Andi',
            'nisn' => '222',
            'nama_ortu' => 'Orang Tua',
            'kelas' => 'XI',
            'tahun_ajaran_id' => $ta->id,
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
        ]);

        $mapel1 = MataPelajaran::create(['nama' => 'Matematika']);
        $mapel2 = MataPelajaran::create(['nama' => 'IPA']);

        Penilaian::create([
            'siswa_id' => $siswa1->id,
            'mapel_id' => $mapel1->id,
            'semester' => 1,
            'hadir' => 10,
            'sakit' => 0,
            'izin' => 0,
            'alpha' => 0,
        ]);
        Penilaian::create([
            'siswa_id' => $siswa2->id,
            'mapel_id' => $mapel2->id,
            'semester' => 1,
            'hadir' => 10,
            'sakit' => 0,
            'izin' => 0,
            'alpha' => 0,
        ]);

        $this->actingAs($admin);

        $response = $this->get('/penilaian?nama=Budi');
        $response->assertOk();
        $response->assertSee('Budi');
        $response->assertDontSee('Andi');

        $response = $this->get('/penilaian?kelas=XI');
        $response->assertOk();
        $response->assertSee('Andi');
        $response->assertDontSee('Budi');

        $response = $this->get('/penilaian?mapel=Matematika');
        $response->assertOk();
        $response->assertSee('Matematika');
        $response->assertDontSee('<td>IPA</td>', false);
    }
}
