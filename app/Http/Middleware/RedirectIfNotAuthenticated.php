<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Ensure sanctum_token exists in session (for AJAX calls using Bearer token)
            // This is necessary because 'Remember Me' cookie restores the session but not values inside it
            if (!session()->has('sanctum_token')) {
                /** @var \App\Models\User $user */
                $user = Auth::user();
                // Clean old tokens to prevent bloating and create a new one
                $user->tokens()->delete();
                $token = $user->createToken('web_session')->plainTextToken;
                session(['sanctum_token' => $token]);
            }

            return $next($request);
        }

        return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
    }
}
