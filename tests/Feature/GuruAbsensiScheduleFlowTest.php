<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Pengajaran;
use App\Models\Jadwal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruAbsensiScheduleFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_can_navigate_schedule_and_change_date(): void
    {
        $user = User::factory()->create(['role' => 'guru']);
        $guru = Guru::create([
            'nuptk' => '200',
            'nama' => 'Guru Test',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
            'user_id' => $user->id,
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
        Pengajaran::create([
            'guru_id' => $guru->id,
            'mapel_id' => $mapel->id,
            'kelas' => $kelas->nama,
        ]);
        $jadwal = Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ]);

        // Dashboard contains link to schedule
        $this->actingAs($user)
            ->get('/dashboard')
            ->assertSee('/absensi/pelajaran');

        // Schedule page shows the lesson
        $this->actingAs($user)
            ->get('/absensi/pelajaran')
            ->assertOk()
            ->assertSee($mapel->nama);

        // Absen form with custom date is visible
        $date = '2024-08-20';
        $this->actingAs($user)
            ->get('/absensi/pelajaran/'.$jadwal->id.'?tanggal='.$date)
            ->assertOk()
            ->assertSee('value="'.$date.'"', false);
    }
}

