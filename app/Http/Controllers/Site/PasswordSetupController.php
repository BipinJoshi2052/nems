<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Jobs\ProvisionTenantJob;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PasswordSetupController extends Controller
{
    public function showForm()
    {
        if (!session('setup_password_email')) {
            return redirect()->route('site.register');
        }

        return view('site.auth.setup-password');
    }

    public function store(Request $request)
    {
        $email = session('setup_password_email');
        if (!$email) {
            return redirect()->route('site.register');
        }

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->letters()->numbers()->symbols()],
        ]);

        $user = DB::table('users')->where('email', $email)->first();
        if (!$user) {
            return redirect()->route('site.register');
        }

        $hash = Hash::make($validated['password']);

        DB::transaction(function () use ($user, $hash) {
            DB::table('users')
                ->where('id', $user->id)
                ->update(['password' => $hash]);

            DB::table('password_histories')->insert([
                'user_id' => $user->id,
                'user_type' => 'users',
                'password_hash' => $hash,
                'created_at' => now(),
            ]);

            DB::table('tenants')
                ->where('id', $user->tenant_id)
                ->update(['status' => 'provisioning']);
        });

        // Automatically log the user in using the unified platform guard
        $platformUser = User::find($user->id);
        Auth::guard('platform')->login($platformUser);

        ProvisionTenantJob::dispatch($user->tenant_id, session('setup_password_plan_id'));

        session(['provisioning_tenant_id' => $user->tenant_id]);
        session()->forget(['setup_password_email', 'setup_password_plan_id']);

        return redirect()->route('site.provisioning');
    }
}
