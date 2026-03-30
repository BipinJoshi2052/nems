<?php

namespace App\Platform\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\TenantSetupMail;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::latest()->paginate(10);

        return view('platform.tenants.index', compact('tenants'));
    }

    public function create()
    {
        $plans = DB::table('plans')->get();

        return view('platform.tenants.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:tenants,email',
            'subdomain' => 'required|string|max:63|unique:tenants,subdomain|regex:/^[a-z0-9-]+$/',
            'vertical' => 'required|in:montessori',
            'plan_id' => 'required|exists:plans,id',
        ]);

        $token = Str::random(64);
        $tenantId = Str::uuid()->toString();
        // dd($tenantId);

        DB::transaction(function () use ($validated, $tenantId, $token) {
            // Pre-create the pending tenant record
            DB::table('tenants')->insert([
                'id' => $tenantId,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subdomain' => $validated['subdomain'],
                'status' => 'pending',
                'vertical' => $validated['vertical'],
                'created_at' => now(),
                'updated_at' => now(),
                'data' => json_encode(['plan_id' => $validated['plan_id']]),
            ]);

            DB::table('tenant_setup_tokens')->insert([
                'tenant_id' => $tenantId,
                'token' => hash('sha256', $token),
                'expires_at' => now()->addHours(48),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Mail::to($validated['email'])->send(new TenantSetupMail($token, $validated['name'], $validated['plan_id']));
        });

        return redirect()->route('platform.tenants.show', $tenantId)->with('status', 'Tenant setup invitation sent successfully!');
    }

    public function show($id)
    {
        $tenant = Tenant::findOrFail($id);

        return view('platform.tenants.show', compact('tenant'));
    }

    public function toggleStatus($id)
    {
        $tenant = Tenant::findOrFail($id);

        $newStatus = $tenant->status === 'active' ? 'suspended' : 'active';

        // Only toggle if it's not pending
        if ($tenant->status === 'pending') {
            return back()->with('error', 'Cannot toggle status of a pending tenant invitation.');
        }

        $tenant->update(['status' => $newStatus]);

        return back()->with('status', 'Tenant has been '.($newStatus === 'active' ? 'activated' : 'suspended').' successfully.');
    }
}
