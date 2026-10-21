<?php

namespace Tests\Feature;

use App\Models\Tugas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlobalSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_search_does_not_return_the_user_directory(): void
    {
        $admin = User::factory()->admin()->create(['nama' => 'Admin Satu']);
        $employee = User::factory()->create(['nama' => 'Andi Karyawan']);

        $this->actingAs($employee, 'sanctum')
            ->getJson('/api/global-search?query=an')
            ->assertOk()
            ->assertJsonCount(0, 'users');
    }

    public function test_employee_search_only_returns_their_own_tasks(): void
    {
        $admin = User::factory()->admin()->create();
        $employee = User::factory()->create();
        $other = User::factory()->create();

        Tugas::create([
            'user_id' => $employee->id,
            'created_by' => $admin->id,
            'tugas' => 'Laporan bulanan saya',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addDay(),
            'status' => Tugas::STATUS_PENDING,
        ]);

        Tugas::create([
            'user_id' => $other->id,
            'created_by' => $admin->id,
            'tugas' => 'Laporan bulanan orang lain',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => now()->addDay(),
            'status' => Tugas::STATUS_PENDING,
        ]);

        $response = $this->actingAs($employee, 'sanctum')
            ->getJson('/api/global-search?query=laporan')
            ->assertOk();

        $this->assertCount(1, $response->json('tasks'));
        $this->assertSame('Laporan bulanan saya', $response->json('tasks.0.title'));
    }
}
