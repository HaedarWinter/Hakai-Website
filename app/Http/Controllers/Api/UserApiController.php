<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\StatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::withCount([
            'tugas' => fn($query) => $query->withTrashed()
        ])
            ->orderByDesc('is_super_admin')  // Super Admin first
            ->orderByRaw("CASE WHEN jabatan = 'Admin' THEN 1 ELSE 2 END")  // Then Admin before Karyawan
            ->orderBy('nama', 'asc')  // Then alphabetically
            ->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->whereNull('deleted_at') // Abaikan record yang sudah dihapus
            ],
            'jabatan' => 'required|in:Admin,Karyawan',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'jabatan' => $request->jabatan,
            'password' => Hash::make($request->password),
            'is_tugas' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->whereNull('deleted_at')->ignore($user->id)
            ],
            'jabatan' => 'required|in:Admin,Karyawan',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = [
            'nama' => $request->nama,
            'email' => $request->email,
        ];

        // Optimization: Check if data is actually changing
        $willChange =
            $user->nama !== $request->nama ||
            $user->email !== $request->email ||
            $user->jabatan !== $request->jabatan ||
            $request->filled('password');

        if (!$willChange) {
            return response()->json([
                'success' => true,
                'message' => 'Tidak ada perubahan data',
                'data' => $user
            ]);
        }

        // Super Admin Check: Only Super Admin can demote another Admin
        if ($user->isAdmin() && $request->jabatan !== 'Admin') {
            if (!auth()->user()->isSuperAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya Super Admin yang berhak menurunkan jabatan Admin lain.',
                ], 403);
            }
        }

        // Super Admin Check: Only Super Admin can promote to Admin
        if (!$user->isAdmin() && $request->jabatan === 'Admin') {
            if (!auth()->user()->isSuperAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya Super Admin yang berhak menaikkan jabatan menjadi Admin.',
                ], 403);
            }
        }

        // Prevent self-demotion: If editing own account, enforce 'Admin' role or keep existing
        if (auth()->id() === $user->id) {
            if ($request->jabatan !== 'Admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Demi keamanan, Anda tidak dapat mengubah jabatan akun sendiri menjadi Karyawan.',
                ], 403);
            }
            // Ensure it stays Admin, though strictly not needed if we reject non-Admin above
            $updateData['jabatan'] = 'Admin';
        } else {
            $updateData['jabatan'] = $request->jabatan;
        }

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if (auth()->id() === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus akun sendiri'
            ], 403);
        }

        // Super Admin Check: Only Super Admin can delete another Admin
        if ($user->isAdmin()) {
            if (!auth()->user()->isSuperAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya Super Admin yang berhak menghapus akun Admin lain.'
                ], 403);
            }
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    /**
     * Toggle Super Admin status for a user.
     * Only accessible by current Super Admins.
     */
    public function toggleSuperAdmin(Request $request, User $user)
    {
        // Only Super Admin can access this
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Hanya Super Admin yang dapat melakukan ini.'
            ], 403);
        }

        // Target must be an Admin
        if (!$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya user dengan jabatan Admin yang dapat dijadikan Super Admin.'
            ], 400);
        }

        // Cannot remove Super Admin status from self
        if (auth()->id() === $user->id && $user->is_super_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat mencabut status Super Admin dari diri sendiri.'
            ], 403);
        }

        // Toggle the status
        $user->is_super_admin = !$user->is_super_admin;
        $user->save();

        $statusText = $user->is_super_admin ? 'diberikan' : 'dicabut';

        return response()->json([
            'success' => true,
            'message' => "Hak Super Admin berhasil {$statusText} untuk {$user->nama}.",
            'data' => $user
        ]);
    }

    /**
     * Export users to Excel
     */
    public function excel()
    {
        $filename = 'Laporan_Data_User_' . now()->format('Ymd_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\UserExport, $filename);
    }

    /**
     * Export users to PDF
     */
    public function pdf(StatisticsService $statsService)
    {
        $users = User::withCount([
            'tugas as total_tugas',
            'tugas as tugas_selesai' => function ($query) {
                $query->where('status', 'approved');
            },
            'tugas as tugas_pending' => function ($query) {
                $query->where('status', 'pending');
            }
        ])
            ->whereNull('deleted_at')
            ->orderByDesc('jabatan')
            ->orderBy('nama')
            ->get();

        $stats = $statsService->calculateUserStats($users);

        $data = [
            'user' => $users, // View expects 'user'
            'stats' => $stats,
            'tanggal' => now()->format('d F Y'),
            'jam' => now()->format('H:i:s'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.user.pdf', $data)
            ->setPaper('A4', 'landscape');

        return $pdf->download('Laporan_Data_User_' . now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Generate Password Reset Token for WhatsApp
     */
    public function generateResetToken(User $user)
    {
        // Only Admin can do this (Middleware will handle basic auth check)
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        // Generate Token using standard Password Broker
        $token = \Illuminate\Support\Facades\Password::createToken($user);

        // Create Reset Link
        // Assuming route 'password.reset' is typically defined by standard auth scaffolding or needs to be manual
        // Default Laravel UI auth uses 'password.reset' name
        $resetLink = url(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ], false));

        return response()->json([
            'success' => true,
            'message' => 'Token generated successfully',
            'data' => [
                'user' => $user->nama,
                'no_hp' => $user->no_hp,
                'reset_link' => $resetLink
            ]
        ]);
    }
}
