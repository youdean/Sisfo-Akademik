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
            'nip' => '123',
            'nama' => 'Guru Test',
            'tanggal_lahir' => '1980-01-01',
        ]);
        $mapel = MataPelajaran::create(['nama' => 'Matematika']);
        $kelas = Kelas::create(['nama' => '10A']);

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
            'nip' => '111',
            'nama' => 'Guru A',
            'tanggal_lahir' => '1980-01-01',
        ]);
        $guruB = Guru::create([
            'nip' => '222',
            'nama' => 'Guru B',
            'tanggal_lahir' => '1985-01-01',
        ]);
        $mapel = MataPelajaran::create(['nama' => 'IPA']);
        $kelas = Kelas::create(['nama' => '10A']);

        $this->actingAs($user)->post('/jadwal', [
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guruA->id,
            'hari' => 'Senin',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ])->assertRedirect('/jadwal');

        $this->actingAs($user)->post('/jadwal', [
            'kelas_id' => $kelas->id,
            'mapel_id' => $mapel->id,
            'guru_id' => $guruB->id,
            'hari' => 'Selasa',
            'jam_mulai' => '07:00',
            'jam_selesai' => '08:00',
        ])->assertRedirect('/jadwal');

        $this->assertEquals(1, Pengajaran::count());
        $this->assertDatabaseHas('pengajaran', [
            'guru_id' => $guruB->id,
            'mapel_id' => $mapel->id,
            'kelas' => $kelas->nama,
        ]);
    }
}
