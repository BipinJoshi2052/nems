<?php

namespace App\Http\Middleware\Tenant;

use App\Core\Services\JwtService;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateJwt
{
    public function __construct(protected JwtService $jwt) {}

    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $payload = $this->jwt->decodeToken($token);

        if (! $payload || ! isset($payload['sub'])) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user = User::find($payload['sub']);

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // Manually log the user in for the current request
        Auth::setUser($user);

        return $next($request);
    }
}
