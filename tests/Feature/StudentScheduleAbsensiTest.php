<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\TahunAjaran;
use App\Models\AttendanceSession;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentScheduleAbsensiTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_absen_from_schedule(): void
    {
        Carbon::setTestNow('2024-07-01 07:30:00'); // Monday during class

        $user = User::factory()->create(['role' => 'siswa']);
        $guru = Guru::create([
            'nuptk' => '1',
            'nama' => 'Guru',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
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
        $siswa = Siswa::create([
            'nama' => 'Siswa 1',
            'nisn' => '123',
            'kelas' => $kelas->nama,
            'tahun_ajaran_id' => $ta->id,
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
            'user_id' => $user->id,
        ]);
        $jadwal = Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ]);

        AttendanceSession::create([
            'jadwal_id' => $jadwal->id,
            'tanggal' => now()->toDateString(),
            'password' => '123456',
            'opened_at' => now(),
        ]);

        $this->actingAs($user)
            ->post('/saya/jadwal/'.$jadwal->id.'/absen', [
                'status' => 'Hadir',
                'password' => '123456',
            ])
            ->assertRedirect('/saya/jadwal');

        $this->assertDatabaseHas('absensi', [
            'siswa_id' => $siswa->id,
            'mapel_id' => $mapel->id,
            'status' => 'Hadir',
            'tanggal' => now()->toDateString(),
        ]);
    }

    public function test_student_cannot_access_absen_form_after_end_time(): void
    {
        Carbon::setTestNow('2024-07-01 08:30:00'); // After 08:00 end time

        $user = User::factory()->create(['role' => 'siswa']);
        $guru = Guru::create([
            'nuptk' => '1',
            'nama' => 'Guru',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
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
            'kelas' => $kelas->nama,
            'tahun_ajaran_id' => $ta->id,
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
            'user_id' => $user->id,
        ]);
        $jadwal = Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ]);

        $this->actingAs($user)
            ->get('/saya/jadwal/'.$jadwal->id.'/absen')
            ->assertForbidden();
    }

    public function test_student_cannot_absen_before_session_open(): void
    {
        Carbon::setTestNow('2024-07-01 07:30:00');

        $user = User::factory()->create(['role' => 'siswa']);
        $guru = Guru::create([
            'nuptk' => '1',
            'nama' => 'Guru',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
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
            'kelas' => $kelas->nama,
            'tahun_ajaran_id' => $ta->id,
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
            'user_id' => $user->id,
        ]);
        $jadwal = Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ]);

        $this->actingAs($user)
            ->post('/saya/jadwal/'.$jadwal->id.'/absen', [
                'status' => 'Hadir',
                'password' => '000000',
            ])
            ->assertForbidden();
    }

    public function test_student_must_provide_correct_password(): void
    {
        Carbon::setTestNow('2024-07-01 07:30:00');

        $user = User::factory()->create(['role' => 'siswa']);
        $guru = Guru::create([
            'nuptk' => '1',
            'nama' => 'Guru',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
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
            'kelas' => $kelas->nama,
            'tahun_ajaran_id' => $ta->id,
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
            'user_id' => $user->id,
        ]);
        $jadwal = Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ]);

        AttendanceSession::create([
            'jadwal_id' => $jadwal->id,
            'tanggal' => now()->toDateString(),
            'password' => '123456',
            'opened_at' => now(),
        ]);

        $this->actingAs($user)
            ->post('/saya/jadwal/'.$jadwal->id.'/absen', [
                'status' => 'Hadir',
                'password' => '000000',
            ])
            ->assertForbidden();
    }

    public function test_session_invalid_after_closed(): void
    {
        Carbon::setTestNow('2024-07-01 07:30:00');

        $user = User::factory()->create(['role' => 'siswa']);
        $guru = Guru::create([
            'nuptk' => '1',
            'nama' => 'Guru',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
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
            'kelas' => $kelas->nama,
            'tahun_ajaran_id' => $ta->id,
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
            'user_id' => $user->id,
        ]);
        $jadwal = Jadwal::create([
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ]);

        AttendanceSession::create([
            'jadwal_id' => $jadwal->id,
            'tanggal' => now()->toDateString(),
            'password' => '123456',
            'opened_at' => now()->subMinutes(10),
            'closed_at' => now()->subMinute(),
        ]);

        $this->actingAs($user)
            ->post('/saya/jadwal/'.$jadwal->id.'/absen', [
                'status' => 'Hadir',
                'password' => '123456',
            ])
            ->assertForbidden();
    }
}
