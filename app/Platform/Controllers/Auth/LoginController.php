<?php

declare(strict_types=1);

namespace App\Platform\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::guard('platform')->check()) {
            return redirect()->route('platform.dashboard');
        }
        if (Auth::guard('customer')->check() || Auth::check()) {
            return redirect()->route('profile');
        }

        return view('platform.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::query()->with('role')->where('email', $credentials['email'])->first();

        if ($user === null || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => __('These credentials do not match our records.'),
            ])->onlyInput('email');
        }

        if ($user->hasEnabledTwoFactorAuthentication()) {
            $request->session()->put('platform.two_factor.id', $user->id);
            $request->session()->put('platform.two_factor.remember', $request->boolean('remember'));

            return redirect()->route('platform.two-factor.challenge');
        }

        Auth::guard('platform')->login($user, $request->boolean('remember'));
        $request->session()->regenerate();
        // dd($user->role?->name);
        if ($user->isPlatformAdmin()) {
            return redirect()->intended(route('platform.dashboard'));
        }

        // Clear intended URL if it's a platform route
        $intended = $request->session()->get('url.intended');
        if (str_contains((string)$intended, '/platform')) {
            $request->session()->forget('url.intended');
        }

        return redirect()->route('profile');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('platform')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
