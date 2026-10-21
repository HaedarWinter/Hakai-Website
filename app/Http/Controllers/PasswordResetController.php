<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    /**
     * Show forgot password form.
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password request - send reset email or WhatsApp.
     */
    public function sendResetLink(Request $request)
    {
        $deliveryMethod = $request->input('delivery_method', 'email');

        // Validate based on delivery method
        if ($deliveryMethod === 'whatsapp') {
            $request->validate([
                'no_hp' => 'required|string',
            ], [
                'no_hp.required' => 'Nomor WhatsApp tidak boleh kosong',
            ]);

            // Find user by phone number
            $user = User::where('no_hp', $request->no_hp)->first();

            if (!$user) {
                return back()->withErrors(['no_hp' => 'Nomor WhatsApp tidak terdaftar di sistem']);
            }

            $email = $user->email;
        } else {
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ], [
                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Format email tidak valid',
                'email.exists' => 'Email tidak terdaftar di sistem',
            ]);

            $email = $request->email;
            $user = User::where('email', $email)->first();
        }

        // Delete any existing tokens for this email
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Generate new token
        $token = Str::random(64);

        // Store token
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now(),
        ]);

        // Generate reset URL
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $email,
        ], false));

        if ($deliveryMethod === 'whatsapp') {
            // Format phone number for WhatsApp
            $noHp = $user->no_hp;
            $formattedHp = preg_replace('/\D/', '', $noHp);
            if (substr($formattedHp, 0, 1) === '0') {
                $formattedHp = '62' . substr($formattedHp, 1);
            }

            // Return data for frontend to handle (copy + open WA)
            $waUrl = "https://wa.me/{$formattedHp}";

            // Return with both the reset link and WhatsApp URL
            return back()
                ->with('reset_link', $resetUrl)
                ->with('whatsapp_url', $waUrl)
                ->with('user_name', $user->nama)
                ->with('status', 'Link reset password berhasil dibuat!');
        } else {
            // Send email
            Mail::send('emails.password-reset', [
                'user' => $user,
                'resetUrl' => $resetUrl,
            ], function ($message) use ($email, $user) {
                $message->to($email, $user->nama)
                    ->subject('🔐 Reset Password - Hakai');
            });

            return back()->with('status', 'Link reset password telah dikirim ke email Anda!');
        }
    }

    /**
     * Show reset password form.
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Handle password reset.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|max:16|confirmed',
        ], [
            'email.exists' => 'Email tidak terdaftar',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 8 karakter',
            'password.max' => 'Password maksimal 16 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sama',
        ]);

        // Check token validity
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenData) {
            return back()->withErrors(['email' => 'Token reset tidak valid atau sudah kadaluarsa']);
        }

        // Check if token matches
        if (!Hash::check($request->token, $tokenData->token)) {
            return back()->withErrors(['email' => 'Token reset tidak valid']);
        }

        // Check if token is expired (1 hour)
        if (Carbon::parse($tokenData->created_at)->addHour()->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Token reset sudah kadaluarsa. Silakan request ulang.']);
        }

        // Update password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login dengan password baru.');
    }
}
