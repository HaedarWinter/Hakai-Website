<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        return $this->show(auth()->user());
    }

    public function show(User $user)
    {
        // Access Control:
        // 1. Admin can view everyone.
        // 2. User can view themselves.
        // 3. User can view other Karyawan (but NOT Admins).
        $currentUser = auth()->user();
        $canView = $currentUser->isAdmin() || $currentUser->id === $user->id || (! $user->isAdmin());

        if (! $canView) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki izin untuk melihat profil ini.');
        }

        // Calculate Stats
        $totalTasks = Tugas::where('user_id', $user->id)->count();
        $completedTasks = Tugas::where('user_id', $user->id)->where('status', 'approved')->count();
        $pendingTasks = Tugas::where('user_id', $user->id)->whereIn('status', ['pending', 'submitted'])->count();

        // Calculate Efficiency
        $efficiency = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        // Recent Activity
        $recentActivities = Tugas::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Viewing mode (read-only if not own profile)
        $isOwnProfile = auth()->id() === $user->id;

        // Fetch other users for "Team Sidebar" (exclude displayed user)
        $otherUsersQuery = User::where('id', '!=', $user->id);

        // If current user is NOT Admin, hide Admins from the list
        if (! $currentUser->isAdmin()) {
            $otherUsersQuery->where('jabatan', '!=', 'Admin');
        }

        $otherUsers = $otherUsersQuery->orderBy('nama')
            ->limit(10)
            ->get();

        return view('profile.index', compact('user', 'totalTasks', 'completedTasks', 'pendingTasks', 'efficiency', 'recentActivities', 'isOwnProfile', 'otherUsers'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'tanggal_bergabung' => ['nullable', 'date'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Handle Photo Upload
        if ($request->hasFile('photo')) {
            if ($user->profile_photo_path) {
                Storage::delete('public/'.$user->profile_photo_path);
            }
            $path = $request->file('photo')->store('profile-photos', 'public');
            $validated['profile_photo_path'] = $path;
        }

        // Remove photo from validated array to avoid updating it twice if not present
        if (! $request->hasFile('photo')) {
            unset($validated['photo']);
        }

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        auth()->user()->tokens()->delete();
        \Illuminate\Support\Facades\Auth::logoutOtherDevices($request->password);

        return back()->with('success', 'Password berhasil diubah!');
    }
}
