<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbsensiRekapTest extends TestCase
{
    use RefreshDatabase;

    public function test_rekap_page_can_be_opened(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get('/absensi/rekap');

        $response->assertOk();
        $response->assertSee('Rekap Absensi');
    }
}
