<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        // For now, this is a placeholder. 
        // In the real app, we would fetch the subscription from the central DB.
        // However, the tenant context is already initialized.
        
        $tenant = tenant();
        $subscription = \App\Models\Subscription::where('tenant_id', $tenant->getTenantKey())
            ->with('plan')
            ->latest()
            ->first();

        // Check if user has school_admin role
        if (!auth()->user()->hasRole('school_admin')) {
            abort(403, 'Unauthorized access to subscription details.');
        }

        return view('tenant.subscription.index', compact('tenant', 'subscription'));
    }
}
