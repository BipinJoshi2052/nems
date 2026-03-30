<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetupTokenController extends Controller
{
    public function verify(Request $request, $token)
    {
        $hashedToken = hash('sha256', $token);

        $setupRecord = DB::table('tenant_setup_tokens')
            ->where('token', $hashedToken)
            ->first();

        if (!$setupRecord) {
            return redirect()->route('site.register')->withErrors(['error' => 'Invalid setup token.']);
        }

        if ($setupRecord->used_at) {
            return redirect()->route('site.register')->withErrors(['error' => 'This setup link has already been used.']);
        }

        if (now()->greaterThan($setupRecord->expires_at)) {
            return redirect()->route('site.register')->withErrors(['error' => 'This setup link has expired.']);
        }

        $tenant = DB::table('tenants')->where('id', $setupRecord->tenant_id)->first();
        if (!$tenant || !$tenant->email) {
            return redirect()->route('site.register')->withErrors(['error' => 'Could not find associated institution.']);
        }

        // Token matches and is valid. We integrate this admin-invited user into the standard password flow.
        DB::transaction(function () use ($setupRecord, $tenant, $request) {
            // Mark token used
            DB::table('tenant_setup_tokens')
                ->where('id', $setupRecord->id)
                ->update(['used_at' => now()]);

            // Update Tenant
            DB::table('tenants')
                ->where('id', $tenant->id)
                ->update(['status' => 'pending_password']);

            // Ensure Platform user exists so PasswordSetupController succeeds
            $exists = DB::table('users')->where('email', $tenant->email)->exists();
            if (!$exists) {
                $roleId = DB::table('platform_roles')->where('name', 'Tenant')->value('id');
                DB::table('users')->insert([
                    'name' => $tenant->name . ' Admin',
                    'email' => $tenant->email,
                    'password' => '', // Set by PasswordSetupController
                    'role_id' => $roleId,
                    'tenant_id' => $tenant->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('platform_tenant_users')->insert([
                    'tenant_id' => $tenant->id,
                    'email' => $tenant->email,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        // Parse Plan injection from the URL
        $planId = $request->query('plan');

        // Prime the password setup sessions
        session([
            'setup_password_email' => $tenant->email,
            'setup_password_plan_id' => $planId,
        ]);

        return redirect()->route('site.setup-password.show')->with('status', 'Token verified! Please secure your account.');
    }
}
