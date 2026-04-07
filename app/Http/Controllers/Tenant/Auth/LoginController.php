<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Core\Services\JwtService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __construct(protected JwtService $jwt) {}

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
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
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
            'type' => 'refresh',
        ], 604800); // 7 days

        // Fetch role name if role_id exists
        $roleName = 'staff';
        if ($user->is_owner) {
            $roleName = 'admin';
        } elseif ($user->role_id) {
            $role = DB::table('roles')->where('id', $user->role_id)->first();
            if ($role) {
                $roleName = $role->name;
            }
        }

        // Return token and set HttpOnly cookie
        return response()->json([
            'access_token' => $accessToken,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $roleName,
                'userType' => $user->user_type ?? 'staff',
                'language_preference' => $user->language_preference ?? 'en',
                'is_setup_complete' => (bool) ($user->is_setup_complete ?? false),
            ],
            'redirect' => '/dashboard',
        ])->withCookie(cookie(
            'tenant_refresh_token',
            $refreshToken,
            10080, // 7 days (in minutes)
            '/',
            null,
            false, // Secure (False for local development over HTTP)
            true, // HttpOnly
            false,
            'Lax' // SameSite: Lax for cross-port dev compatibility
        ));
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->cookie('tenant_refresh_token');
        if (! $refreshToken) {
            return response()->json(['message' => 'No refresh token.'], 401);
        }

        try {
            $payload = $this->jwt->decodeToken($refreshToken);
            if (! $payload || ($payload['type'] ?? '') !== 'refresh') {
                return response()->json(['message' => 'Invalid refresh token.'], 401);
            }

            $user = DB::table('users')->where('id', $payload['sub'])->first();
            if (! $user) {
                return response()->json(['message' => 'User no longer exists.'], 401);
            }

            // Generate new Access Token
            $accessToken = $this->jwt->createToken([
                'sub' => $user->id,
                'email' => $user->email,
                'tenant' => tenant('id'),
            ]);

            // Fetch role name if role_id exists
            $roleName = 'staff';
            if ($user->is_owner) {
                $roleName = 'admin';
            } elseif ($user->role_id) {
                $role = DB::table('roles')->where('id', $user->role_id)->first();
                if ($role) {
                    $roleName = $role->name;
                }
            }

            return response()->json([
                'access_token' => $accessToken,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $roleName,
                    'userType' => $user->user_type ?? 'staff',
                    'language_preference' => $user->language_preference ?? 'en',
                    'is_setup_complete' => (bool) ($user->is_setup_complete ?? false),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Auth refresh failed.'], 401);
        }
    }

    public function logout(Request $request)
    {
        return response()->json(['message' => 'Logged out successfully'])
            ->withoutCookie('tenant_refresh_token');
    }

    public function setupPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->mixedCase()->letters()->numbers()->symbols()],
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return response()->json(['message' => 'Invalid or expired setup link.'], 422);
        }

        // Logic check: Token expiry (48 hours, as per invitation)
        if (now()->subHours(48)->gt($record->created_at)) {
            return response()->json(['message' => 'Setup link has expired.'], 422);
        }

        $user = DB::table('users')->where('email', $request->email)->first();
        if (! $user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        DB::transaction(function () use ($user, $request) {
            $hash = Hash::make($request->password);
            
            DB::table('users')->where('id', $user->id)->update([
                'password' => $hash,
                'email_verified_at' => now(),
                'updated_at' => now(),
            ]);

            // Clear password reset tokens
            DB::table('password_reset_tokens')->where('email', $user->email)->delete();

            // Log history
            DB::table('password_histories')->insert([
                'user_id' => $user->id,
                'user_type' => 'tenant_user',
                'password_hash' => $hash,
                'created_at' => now(),
            ]);
        });

        return response()->json(['message' => 'Account setup complete! You can now sign in.']);
    }
}
