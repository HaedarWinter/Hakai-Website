<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (! is_string($query) || strlen($query) < 2) {
            return response()->json(['tasks' => [], 'users' => []]);
        }

        /** @var User $user */
        $user = Auth::user();

        // Search Tasks (access control applied)
        $tasksQuery = Tugas::query()
            ->with(['user:id,nama,profile_photo_path'])
            ->where('tugas', 'like', "%{$query}%");

        if (! $user->isAdmin()) {
            $tasksQuery->where('user_id', $user->id);
        }

        $tasks = $tasksQuery->latest()->take(5)->get(['id', 'tugas', 'status', 'user_id']);

        // Only admins may search the user directory. Employees would otherwise get
        // every colleague's id, email and photo from a two-letter query.
        $users = collect();
        if ($user->isAdmin()) {
            $users = User::query()
                ->where(function ($q) use ($query) {
                    $q->where('nama', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%");
                })
                ->take(5)
                ->get(['id', 'nama', 'profile_photo_path', 'jabatan', 'email']);
        }

        $formattedTasks = $tasks->map(function ($task) {
            return [
                'type' => 'task',
                'id' => $task->id,
                'title' => $task->tugas,
                'subtitle' => ($task->user?->nama ?? 'Pengguna dihapus').' - '.ucfirst($task->status),
                'url' => route('tugas.index').'?q='.urlencode($task->tugas),
            ];
        });

        $formattedUsers = $users->map(function ($user) {
            return [
                'type' => 'user',
                'id' => $user->id,
                'title' => $user->nama,
                'subtitle' => ucfirst($user->jabatan),
                'image' => $user->profile_photo_path,
                'url' => route('profile.show', $user->id),
            ];
        });

        return response()->json([
            'tasks' => $formattedTasks,
            'users' => $formattedUsers,
        ]);
    }
}
