<?php

declare(strict_types=1);

namespace App\Platform\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorSetupController extends Controller
{
    public function show(Request $request): View
    {
        $google2fa = new Google2FA;
        $secret = $google2fa->generateSecretKey();
        $request->session()->put('platform.two_factor.setup_secret', $secret);

        $admin = Auth::guard('platform')->user();
        $qrUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $admin->email,
            $secret
        );

        return view('platform.auth.two-factor-setup', [
            'qrUrl' => $qrUrl,
            'secret' => $secret,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $secret = $request->session()->get('platform.two_factor.setup_secret');
        if ($secret === null) {
            return redirect()->route('platform.two-factor.setup')->withErrors(['code' => __('Session expired. Start again.')]);
        }

        $google2fa = new Google2FA;
        if (! $google2fa->verifyKey($secret, $request->string('code')->toString())) {
            return back()->withErrors(['code' => __('Invalid authentication code.')]);
        }

        $admin = Auth::guard('platform')->user();
        $admin->two_factor_secret = $secret;
        $admin->two_factor_confirmed_at = now();
        $admin->save();

        $request->session()->forget('platform.two_factor.setup_secret');

        return redirect()->route('platform.dashboard')->with('status', __('Two-factor authentication is enabled.'));
    }
}
