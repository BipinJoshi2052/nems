<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Mail\TenantPasswordResetMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ForgotPasswordController extends Controller
{
    public function showResetForm(Request $request, $token)
    {
        return view('tenant-website.auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Verify user exists in tenant DB
        $user = DB::table('users')->where('email', $request->email)->first();

        if ($user) {
            $token = Str::random(64);
            
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'token' => Hash::make($token),
                    'created_at' => now(),
                ]
            );

            Mail::to($request->email)->queue(new TenantPasswordResetMail($token, $request->email, tenant('name')));
        }

        // Always return success to avoid email enumeration
        return response()->json(['message' => 'If your email is in our system, you will receive a reset link shortly.']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->letters()->numbers()->symbols()],
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return response()->json(['message' => 'Invalid or expired token.'], 422);
        }

        // Logic check: Token expiry (60 mins)
        if (now()->subMinutes(60)->gt($record->created_at)) {
            return response()->json(['message' => 'Token has expired.'], 422);
        }

        DB::transaction(function () use ($request) {
            $user = DB::table('users')->where('email', $request->email)->first();
            $hash = Hash::make($request->password);

            DB::table('users')->where('email', $request->email)->update([
                'password' => $hash,
                'updated_at' => now(),
            ]);

            // Clear password reset tokens
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            // Clear OLD password histories (as requested)
            // We'll keep only the last 3 for auditing, or just clear all except the current one
            DB::table('password_histories')
                ->where('user_id', $user->id)
                ->where('user_type', 'tenant_user') 
                ->delete();

            // Insert new history
            DB::table('password_histories')->insert([
                'user_id' => $user->id,
                'user_type' => 'tenant_user',
                'password_hash' => $hash,
                'created_at' => now(),
            ]);
        });

        return response()->json(['message' => 'Password reset successfully. You can now log in.']);
    }
}
