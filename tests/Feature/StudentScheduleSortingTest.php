<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class StudentScheduleSortingTest extends TestCase
{
    use RefreshDatabase;

    public function test_schedule_sorted_by_start_time(): void
    {
        Carbon::setTestNow('2024-07-01 00:00:00');

        $user = User::factory()->create(['role' => 'siswa']);
        $guru = Guru::create([
            'nuptk' => '1',
            'nama' => 'Guru',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
        ]);
        $mapel = MataPelajaran::create(['nama' => 'Matematika']);
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
        Siswa::create([
            'nama' => 'Siswa 1',
            'nisn' => '123',
            'nama_ortu' => 'Orang Tua',
            'kelas' => $kelas->nama,
            'tahun_ajaran_id' => $ta->id,
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
            'user_id' => $user->id,
        ]);

        // Create schedules out of order
        Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '09:00',
            'jam_selesai' => '10:00',
        ]);
        Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ]);

        $response = $this->actingAs($user)->get('/saya/jadwal');

        $response->assertSeeInOrder(['07:00', '09:00']);
    }
}
