<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Core\Tenant\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        if (function_exists('tenant') && tenant()) {
            /** @var \App\Models\Tenant $t */
            $t = tenant();
            TenantContext::set($t);
        } else {
            TenantContext::clear();
        }

        return $next($request);
    }
}
