<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KelasCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_kelas_index_page_can_be_opened(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        for ($i = 1; $i <= 11; $i++) {
            \App\Models\Kelas::create(['nama' => 'K'.$i]);
        }

        $response = $this->actingAs($user)->get('/kelas');

        $response->assertOk();
        $response->assertSee('Daftar Kelas');
        $response->assertSee('?page=2');
    }
}
