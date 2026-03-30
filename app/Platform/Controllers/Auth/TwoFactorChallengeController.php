<?php

declare(strict_types=1);

namespace App\Platform\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorChallengeController extends Controller
{
    public function show(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('platform.two_factor.id')) {
            return redirect()->route('platform.login');
        }

        return view('platform.auth.two-factor-challenge');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        if (! $request->session()->has('platform.two_factor.id')) {
            return redirect()->route('platform.login');
        }

        $admin = User::query()->findOrFail($request->session()->get('platform.two_factor.id'));

        $google2fa = new Google2FA;

        $secret = $admin->two_factor_secret;
        if ($secret === null || ! $google2fa->verifyKey($secret, $request->string('code')->toString())) {
            return back()->withErrors(['code' => __('Invalid authentication code.')]);
        }

        $remember = (bool) $request->session()->pull('platform.two_factor.remember', false);

        $request->session()->forget('platform.two_factor.id');
        Auth::guard('platform')->login($admin, $remember);
        $request->session()->regenerate();

        return redirect()->intended(route('platform.dashboard'));
    }
}
