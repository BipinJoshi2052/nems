<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OtpController extends Controller
{
    public function showForm()
    {
        if (!session('verify_email')) {
            return redirect()->route('site.register');
        }

        return view('site.auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $email = session('verify_email');
        if (!$email) {
            return redirect()->route('site.register');
        }

        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $otpRecord = DB::table('tenant_otps')->where('email', $email)->orderBy('id', 'desc')->first();

        if (!$otpRecord || now()->greaterThan($otpRecord->expires_at)) {
            return back()->withErrors(['otp' => 'OTP has expired or does not exist. Please resend.']);
        }

        if ($otpRecord->otp !== $request->otp) {
            $attempts = $otpRecord->attempts + 1;
            
            if ($attempts >= 5) {
                // Invalidate OTP
                DB::table('tenant_otps')->where('id', $otpRecord->id)->delete();
                // Log audit (placeholder implementation)
                DB::table('platform_audit_logs')->insert([
                    'action' => 'OTP_FAILED_MAX_ATTEMPTS',
                    'context' => json_encode(['email' => $email]),
                    'ip_address' => $request->ip(),
                    'created_at' => now(),
                ]);
                return back()->withErrors(['otp' => 'Maximum attempts reached. OTP invalidated. Please request a new one.']);
            }

            DB::table('tenant_otps')->where('id', $otpRecord->id)->update(['attempts' => $attempts]);
            return back()->withErrors(['otp' => 'Invalid OTP. Attempt ' . $attempts . ' of 5.']);
        }

        // Success
        DB::table('tenant_otps')->where('id', $otpRecord->id)->delete();
        
        // Update user status
        $user = DB::table('platform_tenant_users')->where('email', $email)->first();
        if ($user) {
            DB::table('tenants')->where('id', $user->tenant_id)->update(['status' => 'pending_password']);
        }

        session(['setup_password_email' => $email]);
        session(['setup_password_plan_id' => session('selected_plan_id')]);
        session()->forget(['verify_email', 'selected_plan_id']);

        return redirect()->route('site.setup-password.show');
    }
}
