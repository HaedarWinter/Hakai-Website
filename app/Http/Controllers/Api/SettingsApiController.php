<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SettingsApiController extends Controller
{
    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = auth()->user();

            $request->validate([
                'nama' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
                'photo' => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
            ]);

            $user->nama = $request->nama;
            $user->email = $request->email;

            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }

                $path = $request->file('photo')->store('profile-photos', 'public');
                $user->profile_photo_path = $path;
            }

            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui.',
                'data' => [
                    'user' => $user->append('profile_photo_url'),
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui profil.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update user profile photo only.
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui.',
                'data' => [
                    'url' => $user->profile_photo_url,
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengupload foto.',
        ], 400);
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini salah.',
                'errors' => [
                    'current_password' => ['Password saat ini salah.'],
                ],
            ], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Kill tokens and sessions from other devices after a password change.
        $user->tokens()->delete();
        Auth::logoutOtherDevices($request->password);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah.',
        ]);
    }
}
