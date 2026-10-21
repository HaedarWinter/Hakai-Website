<?php

namespace Tests\Feature;

use App\Models\TaskComment;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private function makeTask(User $admin, User $employee, string $status = Tugas::STATUS_PENDING): Tugas
    {
        return Tugas::create([
            'user_id' => $employee->id,
            'created_by' => $admin->id,
            'tugas' => 'Contoh tugas',
            'tanggal_mulai' => now()->format('Y-m-d H:i:s'),
            'tanggal_selesai' => now()->addDay()->format('Y-m-d H:i:s'),
            'status' => $status,
            'priority' => 'medium',
        ]);
    }

    public function test_employee_cannot_read_comments_of_another_employees_task(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $task = $this->makeTask($admin, $owner);

        TaskComment::create([
            'tugas_id' => $task->id,
            'user_id' => $owner->id,
            'content' => 'Komentar rahasia',
        ]);

        $this->actingAs($other, 'sanctum')
            ->getJson("/api/tugas/{$task->id}/comments")
            ->assertForbidden();

        $this->actingAs($owner, 'sanctum')
            ->getJson("/api/tugas/{$task->id}/comments")
            ->assertOk()
            ->assertJsonPath('data.0.content', 'Komentar rahasia');
    }

    public function test_employee_cannot_view_another_employees_task(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $task = $this->makeTask($admin, $owner);

        $this->actingAs($other, 'sanctum')
            ->getJson("/api/tugas/{$task->id}")
            ->assertForbidden();
    }

    public function test_admin_can_approve_a_submitted_task(): void
    {
        $admin = User::factory()->admin()->create();
        $employee = User::factory()->create();
        $task = $this->makeTask($admin, $employee, Tugas::STATUS_SUBMITTED);

        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/tugas/{$task->id}/approve")
            ->assertOk();

        $this->assertSame(Tugas::STATUS_APPROVED, $task->fresh()->status);
    }

    public function test_pending_task_cannot_be_approved(): void
    {
        $admin = User::factory()->admin()->create();
        $employee = User::factory()->create();
        $task = $this->makeTask($admin, $employee, Tugas::STATUS_PENDING);

        $this->actingAs($admin, 'sanctum')
            ->postJson("/api/tugas/{$task->id}/approve")
            ->assertStatus(409);

        $this->assertSame(Tugas::STATUS_PENDING, $task->fresh()->status);
    }

    public function test_upload_rejects_executable_files_and_accepts_pdf(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $employee = User::factory()->create();
        $task = $this->makeTask($admin, $employee);

        $this->actingAs($employee, 'sanctum')
            ->post("/api/tugas/{$task->id}/upload", [
                'file' => UploadedFile::fake()->create('shell.php', 10),
            ], ['Accept' => 'application/json'])
            ->assertStatus(422);

        $this->actingAs($employee, 'sanctum')
            ->post("/api/tugas/{$task->id}/upload", [
                'file' => UploadedFile::fake()->create('laporan.pdf', 10, 'application/pdf'),
            ])
            ->assertOk();

        $this->assertSame(Tugas::STATUS_SUBMITTED, $task->fresh()->status);
    }

    public function test_reupload_deletes_the_previous_file(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $employee = User::factory()->create();
        $task = $this->makeTask($admin, $employee);

        $this->actingAs($employee, 'sanctum')->post("/api/tugas/{$task->id}/upload", [
            'file' => UploadedFile::fake()->create('first.pdf', 10, 'application/pdf'),
        ])->assertOk();

        $firstFile = $task->fresh()->karyawan_file;

        // Reject the task so the employee may upload again.
        $this->actingAs($admin, 'sanctum')->postJson("/api/tugas/{$task->id}/reject", [
            'catatan' => 'Perbaiki laporan',
        ])->assertOk();

        $this->actingAs($employee, 'sanctum')->post("/api/tugas/{$task->id}/upload", [
            'file' => UploadedFile::fake()->create('second.pdf', 10, 'application/pdf'),
        ])->assertOk();

        Storage::disk('public')->assertMissing($firstFile);
    }
}
