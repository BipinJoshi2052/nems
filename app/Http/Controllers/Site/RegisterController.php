<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use App\Mail\TenantOtpMail;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function showForm(Request $request)
    {
        return view('site.auth.register', [
            'selectedPlan' => $request->query('plan', 'basic')
        ]);
    }

    public function register(Request $request)
    {
        // IP-based Rate limit handled in route via throttle:3,60

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => 'required|string|alpha_dash|max:50|unique:tenants,subdomain',
            'email' => 'required|email|max:255|unique:platform_tenant_users,email',
            'phone' => 'required|string|max:20',
            'vertical' => 'required|in:montessori',
            'plan_id' => 'sometimes|exists:plans,id',
        ]);

        // If no plan_id in POST, check for 'plan' slug in input or query string
        if (!$request->has('plan_id')) {
            $planSlug = $request->input('plan', $request->query('plan', 'basic'));
            $plan = DB::table('plans')
                ->where('vertical', $validated['vertical'])
                ->where('name', 'like', "%{$planSlug}%")
                ->first();
            $validated['plan_id'] = $plan ? $plan->id : DB::table('plans')->where('vertical', $validated['vertical'])->value('id');
        }

        // Create pending tenant
        $tenantId = Str::uuid()->toString();
        
        DB::transaction(function () use ($validated, $tenantId) {
            DB::table('tenants')->insert([
                'id' => $tenantId,
                'name' => $validated['name'],
                'subdomain' => $validated['subdomain'],
                'email' => $validated['email'],
                'status' => 'pending',
                'vertical' => $validated['vertical'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $roleId = DB::table('platform_roles')->where('name', 'Tenant')->value('id');

            DB::table('users')->insert([
                'name' => "{$validated['name']} Admin",
                'email' => $validated['email'],
                'password' => '', 
                'role_id' => $roleId,
                'tenant_id' => $tenantId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('platform_tenant_users')->insert([
                'tenant_id' => $tenantId,
                'email' => $validated['email'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Generate OTP
            $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
            
            DB::table('tenant_otps')->insert([
                'email' => $validated['email'],
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Send Email
            Mail::to($validated['email'])->send(new TenantOtpMail($otp));
        });

        // Store email and plan in session to verify OTP and eventually setup subscription
        session([
            'verify_email' => $validated['email'],
            'selected_plan_id' => $validated['plan_id']
        ]);

        return redirect()->route('site.verify-otp.show')->with('status', 'OTP sent to your email.');
    }

    public function checkSubdomainAvailability(Request $request)
    {
        $subdomain = $request->query('subdomain');
        if (!$subdomain) {
            return response()->json(['available' => false]);
        }

        $exists = DB::table('tenants')->where('subdomain', $subdomain)->exists();
        
        return response()->json([
            'available' => !$exists
        ]);
    }
}
