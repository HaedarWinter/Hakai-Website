<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\LoginThrottle;
use App\Http\Middleware\RedirectIfNotAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'auth.check' => RedirectIfNotAuthenticated::class,
            'login.throttle' => LoginThrottle::class,
            'no.cache' => \App\Http\Middleware\NoCache::class,
        ]);
        $middleware->append(\App\Http\Middleware\UpdateLastSeen::class);
        // Cookie-authenticated API calls from the panel must pass CSRF checks.
        $middleware->statefulApi();
        // Apply no-cache to all API routes
        $middleware->api([
            \App\Http\Middleware\NoCache::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, $request) {
            if (! $request->expectsJson()) {
                return redirect()->route('dashboard')->with('error', $e->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.');
            }
        });
    })->create();
