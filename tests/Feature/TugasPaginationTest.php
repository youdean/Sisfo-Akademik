<?php
namespace Tests\Feature;

use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\NilaiTugas;
use App\Models\Pengajaran;
use App\Models\Penilaian;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TugasPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_tugas_list_has_pagination_links()
    {
        $user = User::factory()->create(['role' => 'guru']);
        $guru = Guru::create([
            'nuptk' => '1',
            'nama' => 'Guru',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
            'user_id' => $user->id,
        ]);
        $mapel = MataPelajaran::create(['nama' => 'Matematika']);
        Pengajaran::create(['guru_id' => $guru->id, 'mapel_id' => $mapel->id, 'kelas' => '1A']);
        $siswa = [];
        for ($i = 1; $i <= 2; $i++) {
            $siswa[] = Siswa::create([
                'nama' => 'Siswa '.$i,
                'nisn' => str_pad((string)$i, 10, '0', STR_PAD_LEFT),
                'kelas' => '1A',
                'tempat_lahir' => 'Kota',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2000-01-01',
            ]);
        }
        foreach (range(1,11) as $i) {
            foreach ($siswa as $sis) {
                $penilaian = Penilaian::firstOrCreate([
                    'siswa_id' => $sis->id,
                    'mapel_id' => $mapel->id,
                    'semester' => 1
                ]);
                NilaiTugas::create([
                    'penilaian_id' => $penilaian->id,
                    'nama' => 'Tugas '.$i,
                    'nilai' => 80
                ]);
            }
        }

        $response = $this->actingAs($user)->get("/input-nilai/{$mapel->id}/1A/tugas-list");
        $response->assertOk();
        $response->assertSee('?page=2');

        $responsePage2 = $this->actingAs($user)->get("/input-nilai/{$mapel->id}/1A/tugas-list?page=2");
        $responsePage2->assertOk();
    }
}
