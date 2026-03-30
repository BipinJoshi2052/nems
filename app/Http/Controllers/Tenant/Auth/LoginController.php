<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Core\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    public function __construct(protected JwtService $jwt)
    {
    }

    public function showLoginForm()
    {
        return view('tenant-website.auth.login');
    }

    /**
     * API Login Endpoint (JWT)
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt authentication against the TENANT users table
        // The tenant database is already initialized by middleware
        $user = DB::table('users')->where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        // Generate Access Token
        $accessToken = $this->jwt->createToken([
            'sub' => $user->id,
            'email' => $user->email,
            'tenant' => tenant('id'),
        ]);

        // Generate Refresh Token (Longer TTL)
        $refreshToken = $this->jwt->createToken([
            'sub' => $user->id,
            'type' => 'refresh'
        ], 604800); // 7 days

        // Return token and set HttpOnly cookie
        return response()->json([
            'access_token' => $accessToken,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
            'redirect' => '/dashboard'
        ])->withCookie(cookie(
            'tenant_refresh_token',
            $refreshToken,
            10080, // 7 days (in minutes)
            '/',
            null,
            true, // Secure
            true, // HttpOnly
            false,
            'Strict'
        ));
    }

    public function logout(Request $request)
    {
        // Clear the refresh cookie
        return response()->json(['message' => 'Logged out successfully'])
            ->withoutCookie('tenant_refresh_token');
    }
}
