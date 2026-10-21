<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

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
        $throttleKey = 'password-reset:'.$request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return back()->withErrors([
                'email' => 'Terlalu banyak permintaan reset. Silakan coba lagi dalam beberapa menit.',
            ]);
        }

        RateLimiter::hit($throttleKey, 60);

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
        } else {
            $request->validate([
                'email' => 'required|email',
            ], [
                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Format email tidak valid',
            ]);

            $user = User::where('email', $request->email)->first();
        }

        // Same response whether the account exists or not, so the form cannot be
        // used to discover registered emails or phone numbers.
        if (! $user) {
            return back()->with('status', 'Jika data terdaftar, link reset password telah dikirim.');
        }

        $email = $user->email;

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
            $formattedHp = preg_replace('/\D/', '', (string) $noHp);
            if (substr($formattedHp, 0, 1) === '0') {
                $formattedHp = '62'.substr($formattedHp, 1);
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
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sama',
        ]);

        // Check token validity
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $tokenData) {
            return back()->withErrors(['email' => 'Token reset tidak valid atau sudah kadaluarsa']);
        }

        // Check if token matches
        if (! Hash::check($request->token, $tokenData->token)) {
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

        // Invalidate every existing credential after a password reset.
        $user->tokens()->delete();
        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))->where('user_id', $user->id)->delete();
        }

        // Delete token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login dengan password baru.');
    }
}
