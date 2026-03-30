<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\SeoService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(protected SeoService $seo)
    {
    }

    public function index(Request $request)
    {
        $this->seo->setTitle(__('My Profile - Nepal Education System'))
                  ->setDescription(__('Manage your profile and subscription settings.'));

        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $tenant = $user->tenant()->first();
        $domain = $tenant ? \DB::table('domains')->where('tenant_id', $tenant->id)->first() : null;
        $subscription = $tenant ? \DB::table('subscriptions')
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->where('tenant_id', $tenant->id)
            ->select('subscriptions.*', 'plans.name as plan_name')
            ->first() : null;

        return view('site.profile', [
            'seo' => $this->seo,
            'user' => $user,
            'tenant' => $tenant,
            'domain' => $domain,
            'subscription' => $subscription
        ]);
    }
}
