<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Support both web session auth and API token auth (Sanctum)
        if (auth()->check() || auth('sanctum')->check()) {
            $user = auth()->check() ? auth()->user() : auth('sanctum')->user();

            if ($user) {
                $user->last_seen = now();
                $user->save();
            }
        }

        return $next($request);
    }
}
