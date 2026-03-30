<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProvisioningController extends Controller
{
    public function showLoader()
    {
        $tenantId = session('provisioning_tenant_id');
        if (!$tenantId) {
            return redirect()->route('site.register');
        }

        return view('site.auth.provisioning');
    }

    public function status(Request $request)
    {
        $tenantId = session('provisioning_tenant_id');
        if (!$tenantId) {
            return response()->json(['status' => 'error'], 400);
        }

        $tenant = DB::table('tenants')->where('id', $tenantId)->first();
        if (!$tenant) {
            return response()->json(['status' => 'error'], 404);
        }

        // The redirect URL should be the local profile page as requested
        $redirectUrl = route('profile');
        
        return response()->json([
            'status' => $tenant->status,
            'redirect_url' => $redirectUrl,
        ]);
    }
}
