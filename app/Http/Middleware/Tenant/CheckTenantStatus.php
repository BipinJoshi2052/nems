<?php

namespace App\Http\Middleware\Tenant;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTenantStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!function_exists('tenant') || !tenant()) {
            return $next($request);
        }

        /** @var \App\Models\Tenant $tenant */
        $tenant = tenant();

        // If status is expired or suspended
        if (in_array($tenant->status, ['expired', 'suspended'])) {
            // If it's an API request, return 403
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'Institution account is ' . $tenant->status,
                    'message' => 'Please contact your administrator or renew your subscription.'
                ], 403);
            }

            // Exclude the subscription/expiry page itself from the redirect loop
            if (!$request->is('subscription*') && !$request->is('logout')) {
                return redirect()->route('tenant.subscription.index')->with('error', 'Your institution access is currently ' . $tenant->status . '.');
            }
        }

        return $next($request);
    }
}
