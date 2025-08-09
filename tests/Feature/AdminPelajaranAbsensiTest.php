<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPelajaranAbsensiTest extends TestCase
{
    use RefreshDatabase;

    private function createData()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $guru = Guru::create([
            'nuptk' => '1',
            'nama' => 'Guru',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
        ]);
        $ta = TahunAjaran::create([
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);
        $kelas = Kelas::create([
            'nama' => 'X',
            'guru_id' => $guru->id,
            'tahun_ajaran_id' => $ta->id,
        ]);
        $mapel = MataPelajaran::create(['nama' => 'Matematika']);
        $jadwal = Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ]);

        return [$admin, $guru, $kelas, $mapel, $jadwal];
    }

    public function test_admin_can_view_filtered_schedule(): void
    {
        [$admin, , $kelas, $mapel, $jadwal] = $this->createData();

        $response = $this->actingAs($admin)
            ->get('/absensi/pelajaran?hari=Senin&kelas_id='.$kelas->id.'&mapel_id='.$mapel->id);

        $response->assertOk();
        $response->assertSee($mapel->nama);
        $response->assertSee($kelas->nama);
    }

    public function test_admin_can_record_absensi_for_arbitrary_date(): void
    {
        [$admin, , $kelas, $mapel, $jadwal] = $this->createData();

        $siswa = Siswa::create([
            'nama' => 'Siswa 1',
            'nisn' => '1',
            'nama_ortu' => 'Orang Tua',
            'kelas' => $kelas->nama,
            'tahun_ajaran_id' => $kelas->tahun_ajaran_id,
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
        ]);

        $date = '2024-08-15';

        $this->actingAs($admin)
            ->post('/absensi/pelajaran/'.$jadwal->id, [
                'tanggal' => $date,
                'status' => [
                    $siswa->id => 'Hadir'
                ],
            ])
            ->assertRedirect('/absensi/pelajaran/'.$jadwal->id.'?tanggal='.$date);

        $this->assertDatabaseHas('absensi', [
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'tanggal' => $date,
            'status' => 'Hadir',
        ]);
    }
}
