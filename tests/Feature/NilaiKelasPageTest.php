<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Penilaian;
use App\Models\MataPelajaran;
use App\Models\Pengajaran;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NilaiKelasPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_homeroom_teacher_can_view_all_grades_for_their_class(): void
    {
        $ta = TahunAjaran::create([
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);

        $guruUser1 = User::factory()->create(['role' => 'guru']);
        $guru1 = Guru::create([
            'nuptk' => '1',
            'nama' => 'Guru 1',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
            'user_id' => $guruUser1->id,
        ]);

        $guruUser2 = User::factory()->create(['role' => 'guru']);
        $guru2 = Guru::create([
            'nuptk' => '2',
            'nama' => 'Guru 2',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
            'user_id' => $guruUser2->id,
        ]);

        $kelas = Kelas::create([
            'nama' => 'X-1',
            'guru_id' => $guru1->id,
            'tahun_ajaran_id' => $ta->id,
        ]);

        $siswa = Siswa::create([
            'nama' => 'Siswa',
            'nisn' => '123',
            'nama_ortu' => 'Orang Tua',
            'kelas' => $kelas->nama,
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2005-01-01',
            'tahun_ajaran_id' => $ta->id,
        ]);

        $mapel1 = MataPelajaran::create(['nama' => 'Matematika']);
        $mapel2 = MataPelajaran::create(['nama' => 'IPA']);

        Pengajaran::create(['guru_id' => $guru1->id, 'mapel_id' => $mapel1->id, 'kelas' => $kelas->nama]);
        Pengajaran::create(['guru_id' => $guru2->id, 'mapel_id' => $mapel2->id, 'kelas' => $kelas->nama]);

        Penilaian::create([
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel1->id,
            'semester' => 1,
            'hadir' => 10,
            'sakit' => 0,
            'izin' => 0,
            'alpha' => 0,
            'pts' => 80,
            'pat' => 90,
        ]);

        Penilaian::create([
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel2->id,
            'semester' => 1,
            'hadir' => 10,
            'sakit' => 0,
            'izin' => 0,
            'alpha' => 0,
            'pts' => 70,
            'pat' => 80,
        ]);

        $response = $this->actingAs($guruUser1)->get('/nilai-kelas');

        $response->assertOk();
        $response->assertSee('Matematika');
        $response->assertSee('IPA');
        $response->assertSee('75.00');
        $response->assertSee('68.00');
    }
}

