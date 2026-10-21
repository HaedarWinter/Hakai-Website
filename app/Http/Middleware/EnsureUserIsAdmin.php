<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Ensure the authenticated user has admin privileges.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Bearer-token clients authenticate through Sanctum, not the web guard.
        $user = $request->user('sanctum') ?? $request->user() ?? Auth::user();

        if (! $user) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }

        if ($user->isAdmin()) {
            return $next($request);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda tidak memiliki izin untuk melakukan aksi ini.',
            ], 403);
        }

        return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses');
    }
}
