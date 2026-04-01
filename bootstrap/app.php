<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'guest' => \Illuminate\Auth\Middleware\RedirectIfAuthenticated::class,
            'platform.admin' => \App\Http\Middleware\PlatformAdminMiddleware::class,
            'platform.tenant' => \App\Http\Middleware\TenantOnlyMiddleware::class,
            'tenant.status' => \App\Http\Middleware\Tenant\CheckTenantStatus::class,
            'auth.jwt' => \App\Http\Middleware\Tenant\AuthenticateJwt::class,
        ]);

        $middleware->appendToGroup('web', [
            \App\Http\Middleware\SecurityHeadersMiddleware::class,
        ]);

        $middleware->redirectTo(
            guests: fn (\Illuminate\Http\Request $request) => (function_exists('tenant') && tenant())
                ? route('tenant.login')
                : route('login')
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
