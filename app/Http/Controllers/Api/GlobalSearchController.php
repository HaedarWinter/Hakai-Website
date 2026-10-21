<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (strlen($query) < 2) {
            return response()->json(['tasks' => [], 'users' => []]);
        }

        // Search Tasks (Access Control applied)
        $tasksQuery = Tugas::query()
            ->with(['user:id,nama,profile_photo_path']) // Eager load user for display
            ->where('tugas', 'like', "%{$query}%");

        // If not admin/super-admin, only show own tasks or public tasks (adjust logic as per app rules)
        $user = Auth::user();
        if ($user && $user->jabatan === 'karyawan') {
            $tasksQuery->where(function ($q) use ($user) {
                $q->where('user_id', $user->id); // Own tasks
            });
        }

        $tasks = $tasksQuery->latest()->take(5)->get(['id', 'tugas', 'status', 'user_id']);

        // Search Users (Admin searches everyone, Employees might search colleagues)
        $users = User::query()
            ->where('nama', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->take(5)
            ->get(['id', 'nama', 'profile_photo_path', 'jabatan', 'email']);

        // Format results
        $formattedTasks = $tasks->map(function ($task) {
            return [
                'type' => 'task',
                'id' => $task->id,
                'title' => $task->tugas,
                'subtitle' => $task->user->nama . ' - ' . ucfirst($task->status),
                'url' => route('tugas.index') . '?q=' . urlencode($task->tugas)
            ];
        });

        $formattedUsers = $users->map(function ($user) {
            return [
                'type' => 'user',
                'id' => $user->id,
                'title' => $user->nama,
                'subtitle' => ucfirst($user->jabatan),
                'image' => $user->profile_photo_path,
                'url' => route('profile.show', $user->id)
            ];
        });

        return response()->json([
            'tasks' => $formattedTasks,
            'users' => $formattedUsers
        ]);
    }
}
