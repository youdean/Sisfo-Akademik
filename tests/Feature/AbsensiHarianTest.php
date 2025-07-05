<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbsensiHarianTest extends TestCase
{
    use RefreshDatabase;

    public function test_harian_page_can_be_opened(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get('/absensi/harian');

        $response->assertOk();
        $response->assertSee('Absensi Harian');
    }
}
