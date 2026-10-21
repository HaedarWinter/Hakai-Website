<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class LoginThrottle
{
    /**
     * Maximum login attempts before lockout.
     */
    protected int $maxAttempts = 5;

    /**
     * Lockout duration in minutes.
     */
    protected int $decayMinutes = 5;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $this->throttleKey($request);

        if ($this->hasTooManyLoginAttempts($key)) {
            $seconds = $this->availableIn($key);
            $minutes = ceil($seconds / 60);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$minutes} menit.",
                    'retry_after' => $seconds,
                    'locked' => true,
                ], 429);
            }

            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$minutes} menit.",
                    'locked' => true,
                ]);
        }

        return $next($request);
    }

    /**
     * Generate the throttle key for the given request.
     */
    protected function throttleKey(Request $request): string
    {
        $email = strtolower($request->input('email', ''));
        $ip = $request->ip();

        return 'login_attempts:' . sha1($email . '|' . $ip);
    }

    /**
     * Check if too many login attempts have been made.
     */
    protected function hasTooManyLoginAttempts(string $key): bool
    {
        return Cache::get($key, 0) >= $this->maxAttempts;
    }

    /**
     * Get the number of seconds until the key is available again.
     */
    protected function availableIn(string $key): int
    {
        $timer = Cache::get($key . ':timer', now()->timestamp);
        return max(0, $timer - now()->timestamp);
    }

    /**
     * Increment the login attempts for the key.
     */
    public static function hit(Request $request): void
    {
        $email = strtolower($request->input('email', ''));
        $ip = $request->ip();
        $key = 'login_attempts:' . sha1($email . '|' . $ip);

        $attempts = Cache::get($key, 0) + 1;
        $decayMinutes = 5;

        Cache::put($key, $attempts, now()->addMinutes($decayMinutes));
        Cache::put($key . ':timer', now()->addMinutes($decayMinutes)->timestamp, now()->addMinutes($decayMinutes));
    }

    /**
     * Clear the login attempts for the key.
     */
    public static function clear(Request $request): void
    {
        $email = strtolower($request->input('email', ''));
        $ip = $request->ip();
        $key = 'login_attempts:' . sha1($email . '|' . $ip);

        Cache::forget($key);
        Cache::forget($key . ':timer');
    }

    /**
     * Get remaining attempts for the key.
     */
    public static function remainingAttempts(Request $request): int
    {
        $email = strtolower($request->input('email', ''));
        $ip = $request->ip();
        $key = 'login_attempts:' . sha1($email . '|' . $ip);

        $maxAttempts = 5;
        $attempts = Cache::get($key, 0);

        return max(0, $maxAttempts - $attempts);
    }
}
