<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        if (config('app.env') === 'production') {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        $response->headers->set('Content-Security-Policy', $this->contentSecurityPolicy());

        return $response;
    }

    private function contentSecurityPolicy(): string
    {
        $bunny = 'https://fonts.bunny.net';
        $jsdelivr = 'https://cdn.jsdelivr.net';

        $common = implode('; ', [
            "default-src 'self'",
            "img-src 'self' data: blob:",
            "font-src 'self' data: {$bunny}",
        ]);

        if (! app()->environment('local')) {
            return "{$common}; script-src 'self' 'unsafe-inline' 'unsafe-eval' {$jsdelivr}; style-src 'self' 'unsafe-inline' {$bunny} {$jsdelivr}; connect-src 'self'";
        }

        // Local: Vite dev (vite.config.js) + optional Bunny fonts + Bootstrap CDN fallback (welcome.blade)
        $viteHttp = 'http://127.0.0.1:5173 http://localhost:5173 http://0.0.0.0:5173';
        $viteWs = 'ws://127.0.0.1:5173 ws://localhost:5173 ws://0.0.0.0:5173';

        return implode('; ', [
            $common,
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' {$jsdelivr} {$viteHttp}",
            "style-src 'self' 'unsafe-inline' {$bunny} {$jsdelivr} {$viteHttp}",
            "connect-src 'self' {$viteHttp} {$viteWs}",
        ]);
    }
}
