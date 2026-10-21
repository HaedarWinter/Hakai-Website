<?php

namespace App\Policies;

use App\Models\Tugas;
use App\Models\User;

class TugasPolicy
{
    /**
     * Determine if the user can view any tasks.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view tasks
    }

    /**
     * Determine if the user can view the task.
     */
    public function view(User $user, Tugas $tugas): bool
    {
        // Admin can only view tasks they created, employees can only view their assigned tasks
        if ($user->isAdmin()) {
            return $tugas->created_by === $user->id;
        }
        return $tugas->user_id === $user->id;
    }

    /**
     * Determine if the user can create tasks.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine if the user can update the task.
     */
    public function update(User $user, Tugas $tugas): bool
    {
        // Only the admin who created the task can update it
        return $user->isAdmin() && $tugas->created_by === $user->id;
    }

    /**
     * Determine if the user can delete the task.
     * Admin can delete tasks they created, Karyawan can delete their own assigned tasks (move to trash).
     */
    public function delete(User $user, Tugas $tugas): bool
    {
        if ($user->isAdmin()) {
            return $tugas->created_by === $user->id;
        }
        return $tugas->user_id === $user->id;
    }

    /**
     * Determine if the user can upload files to the task.
     */
    public function upload(User $user, Tugas $tugas): bool
    {
        // Only the assigned employee can upload files
        return $tugas->user_id === $user->id;
    }

    /**
     * Determine if the user can approve the task.
     */
    public function approve(User $user, Tugas $tugas): bool
    {
        // Only the admin who created the task can approve it
        return $user->isAdmin() && $tugas->created_by === $user->id;
    }

    /**
     * Determine if the user can reject the task.
     */
    public function reject(User $user, Tugas $tugas): bool
    {
        // Only the admin who created the task can reject it
        return $user->isAdmin() && $tugas->created_by === $user->id;
    }
}

