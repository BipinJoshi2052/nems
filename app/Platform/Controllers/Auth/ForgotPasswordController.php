<?php

namespace App\Platform\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PlatformPasswordResetMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('platform.auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

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

            Mail::to($request->email)->queue(new PlatformPasswordResetMail($token, $request->email));
        }

        return back()->with('status', __('We have emailed your password reset link!'));
    }

    public function showResetForm(Request $request, $token)
    {
        return view('platform.auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->letters()->numbers()->symbols()],
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => __('Invalid or expired token.')]);
        }

        if (now()->subMinutes(60)->gt($record->created_at)) {
            return back()->withErrors(['email' => __('Token has expired.')]);
        }

        DB::transaction(function () use ($request) {
            $user = DB::table('users')->where('email', $request->email)->first();
            $hash = Hash::make($request->password);

            DB::table('users')->where('email', $request->email)->update([
                'password' => $hash,
                'updated_at' => now(),
            ]);

            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            // Clear OLD password histories
            DB::table('password_histories')
                ->where('user_id', $user->id)
                ->where('user_type', 'platform_user') 
                ->delete();

            DB::table('password_histories')->insert([
                'user_id' => $user->id,
                'user_type' => 'platform_user',
                'password_hash' => $hash,
                'created_at' => now(),
            ]);
        });

        return redirect()->route('platform.login')->with('status', __('Password has been reset!'));
    }
}
