<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeen
{
    /**
     * Handle an incoming request.
     *
     * The panel polls several endpoints every minute, so only write when the
     * stored value is actually stale. A raw query avoids touching updated_at.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user() ?? auth('sanctum')->user();

        if ($user && (! $user->last_seen || $user->last_seen->lt(now()->subMinute()))) {
            DB::table($user->getTable())
                ->where('id', $user->id)
                ->update(['last_seen' => now()]);
        }

        return $next($request);
    }
}
