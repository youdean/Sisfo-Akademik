<?php
namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\AbsensiSession;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherExtendedSessionStartTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_start_session_mid_block(): void
    {
        Carbon::setTestNow('2024-07-01 08:30:00');

        $teacherUser = User::factory()->create(['role' => 'guru']);
        $guru = Guru::create([
            'nuptk' => '1',
            'nama' => 'Guru',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
            'user_id' => $teacherUser->id,
        ]);
        $mapel = MataPelajaran::create(['nama' => 'IPA']);
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
        ]);

        $first = Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ]);
        $second = Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '09:00',
        ]);

        $this->actingAs($teacherUser)
            ->post(route('absensi.session.start', $second->id))
            ->assertRedirect(route('absensi.session', $first->id));

        $this->assertDatabaseHas('absensi_sessions', [
            'jadwal_id' => $first->id,
            'tanggal' => '2024-07-01',
            'status_sesi' => 'open',
        ]);
    }

    public function test_session_page_allows_start_until_block_end(): void
    {
        Carbon::setTestNow('2024-07-01 08:30:00');

        $teacherUser = User::factory()->create(['role' => 'guru']);
        $guru = Guru::create([
            'nuptk' => '1',
            'nama' => 'Guru',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
            'user_id' => $teacherUser->id,
        ]);
        $mapel = MataPelajaran::create(['nama' => 'IPA']);
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

        $first = Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ]);
        $second = Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '09:00',
        ]);

        $response = $this->actingAs($teacherUser)
            ->get(route('absensi.session', $second->id));

        $response->assertSee('Mulai Sesi');
        $response->assertDontSee('disabled');
    }
}
