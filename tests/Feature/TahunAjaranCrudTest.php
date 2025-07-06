<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TahunAjaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TahunAjaranCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_edit_and_delete_tahun_ajaran(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->post('/tahun-ajaran', [
            'nama' => '2024/2025',
            'start_date' => '2024-07-01',
            'end_date' => '2025-06-30',
        ]);
        $response->assertRedirect('/tahun-ajaran');
        $ta = TahunAjaran::first();
        $this->assertNotNull($ta);

        $response = $this->actingAs($user)->put('/tahun-ajaran/'.$ta->id, [
            'nama' => '2025/2026',
            'start_date' => '2025-07-01',
            'end_date' => '2026-06-30',
        ]);
        $response->assertRedirect('/tahun-ajaran');
        $this->assertEquals('2025/2026', $ta->fresh()->nama);

        $response = $this->actingAs($user)->delete('/tahun-ajaran/'.$ta->id);
        $response->assertRedirect('/tahun-ajaran');
        $this->assertEquals(0, TahunAjaran::count());
    }
}
