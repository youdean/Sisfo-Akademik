<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FutureAttendanceAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_schedule_shows_disabled_link_for_future_date(): void
    {
        Carbon::setTestNow('2024-07-01 07:00:00');

        $admin = User::factory()->create(['role' => 'admin']);
        $guruUser = User::factory()->create(['role' => 'guru']);
        $guru = Guru::create([
            'nuptk' => '1',
            'nama' => 'Guru',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
            'user_id' => $guruUser->id,
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
        $jadwal = Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Selasa',
            'jam_mulai' => '09:00',
            'jam_selesai' => '10:00',
        ]);

        $response = $this->actingAs($admin)->get('/absensi/pelajaran?tanggal=2024-07-02&hari=Selasa');

        $response->assertOk();
        $response->assertSee('<button class="btn btn-sm btn-primary" disabled>Isi Absen</button>', false);
        $response->assertDontSee(route('absensi.pelajaran.form', ['jadwal' => $jadwal->id, 'tanggal' => '2024-07-02']));
    }

    public function test_future_date_disables_save_button_on_attendance_form(): void
    {
        Carbon::setTestNow('2024-07-01 07:00:00');

        $user = User::factory()->create(['role' => 'guru']);
        $guru = Guru::create([
            'nuptk' => '1',
            'nama' => 'Guru',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
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
        $jadwal = Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ]);

        $response = $this->actingAs($user)->get(route('absensi.pelajaran.form', ['jadwal' => $jadwal->id, 'tanggal' => '2024-07-02']));

        $response->assertOk();
        $response->assertSee('<button class="btn btn-success" disabled>Simpan</button>', false);
    }
}

