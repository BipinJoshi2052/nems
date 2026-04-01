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
        $googleFonts = 'https://fonts.googleapis.com';
        $googleStatic = 'https://fonts.gstatic.com';
        $jsdelivr = 'https://cdn.jsdelivr.net';
        $tailwind = 'https://cdn.tailwindcss.com';
        $uiAvatars = 'https://ui-avatars.com';

        if (! app()->environment('local')) {
            return implode('; ', [
                "default-src 'self'",
                "img-src 'self' data: blob: {$uiAvatars}",
                "font-src 'self' data: {$bunny} {$googleStatic}",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval' {$jsdelivr} {$tailwind}",
                "style-src 'self' 'unsafe-inline' {$bunny} {$googleFonts} {$jsdelivr} {$tailwind}",
                "connect-src 'self'",
            ]);
        }

        // Local: Vite dev (vite.config.js)
        $viteHttp = 'http://localhost:5173 http://127.0.0.1:5173 http://localhost:5174 http://127.0.0.1:5174 http://localhost:5180 http://127.0.0.1:5180';
        $viteWs = 'ws://localhost:5173 ws://127.0.0.1:5173 ws://localhost:5174 ws://127.0.0.1:5174 ws://localhost:5180 ws://127.0.0.1:5180';
        $appDomains = 'http://nems.com http://*.nems.com http://nems.com:8000 http://*.nems.com:8000';

        return implode('; ', [
            "default-src 'self'",
            "img-src 'self' data: blob: {$uiAvatars} {$appDomains}",
            "font-src 'self' data: {$bunny} {$googleStatic} {$viteHttp}",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' {$jsdelivr} {$viteHttp} {$tailwind}",
            "style-src 'self' 'unsafe-inline' {$bunny} {$googleFonts} {$jsdelivr} {$viteHttp} {$tailwind}",
            "connect-src 'self' {$viteHttp} {$viteWs}",
        ]);
    }
}
