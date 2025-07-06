<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\Pengajaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JadwalPengajaranSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_jadwal_creates_pengajaran_record(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $guru = Guru::create([
            'nuptk' => '123',
            'nama' => 'Guru Test',
            'tempat_lahir' => 'Jakarta',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
        ]);
        $mapel = MataPelajaran::create(['nama' => 'Matematika']);
        $wali = Guru::create([
            'nuptk' => '1234',
            'nama' => 'Wali 10',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
        ]);
        $ta = \App\Models\TahunAjaran::create([
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);
        $kelas = Kelas::create(['nama' => 'X', 'guru_id' => $wali->id, 'tahun_ajaran_id' => $ta->id]);

        $response = $this->actingAs($user)->post('/jadwal', [
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guru->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ]);
        $response->assertRedirect('/jadwal');

        $this->assertDatabaseHas('pengajaran', [
            'guru_id' => $guru->id,
            'mapel_id' => $mapel->id,
            'kelas' => $kelas->nama,
        ]);
    }

    public function test_store_jadwal_updates_pengajaran_if_subject_and_class_exist(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $guruA = Guru::create([
            'nuptk' => '111',
            'nama' => 'Guru A',
            'tempat_lahir' => 'Bandung',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1980-01-01',
        ]);
        $guruB = Guru::create([
            'nuptk' => '222',
            'nama' => 'Guru B',
            'tempat_lahir' => 'Jakarta',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1985-01-01',
        ]);
        $mapel = MataPelajaran::create(['nama' => 'IPA']);
        $wali = Guru::create([
            'nuptk' => '1235',
            'nama' => 'Wali 10B',
            'tempat_lahir' => 'Kota',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
        ]);
        $ta = \App\Models\TahunAjaran::create([
            'nama' => '2025/2026',
            'start_date' => '2025-07-01',
            'end_date' => '2026-06-30',
        ]);
        $kelas = Kelas::create(['nama' => 'X', 'guru_id' => $wali->id, 'tahun_ajaran_id' => $ta->id]);

        $this->actingAs($user)->post('/jadwal', [
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guruA->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ])->assertRedirect('/jadwal');

        $response = $this->actingAs($user)
            ->from('/jadwal/create')
            ->post('/jadwal', [
                'kelas_id' => $kelas->id,
                'mapel_id' => $mapel->id,
                'guru_id' => $guruB->id,
                'hari' => 'Selasa',
                'jam_mulai' => '07:00',
                'jam_selesai' => '08:00',
            ]);

        $response->assertRedirect('/jadwal/create');
        $response->assertSessionHas('error');
        $this->assertEquals(1, Pengajaran::count());
        $this->assertDatabaseHas('pengajaran', [
            'guru_id' => $guruA->id,
            'mapel_id' => $mapel->id,
            'kelas' => $kelas->nama,
        ]);
    }
}
