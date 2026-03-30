<?php

declare(strict_types=1);

namespace App\Platform\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function edit(): View
    {
        return view('platform.auth.password');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password:platform'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $admin = Auth::guard('platform')->user();
        $admin->password = $validated['password'];
        $admin->save();

        return redirect()->route('platform.dashboard')->with('status', __('Password updated.'));
    }
}
