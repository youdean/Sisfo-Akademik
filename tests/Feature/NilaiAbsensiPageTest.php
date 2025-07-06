<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NilaiAbsensiPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_page_can_be_opened(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get('/nilai-absensi');

        $response->assertOk();
        $response->assertSee('Nilai Absensi');
    }
}
