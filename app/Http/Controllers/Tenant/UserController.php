<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Update user preferences.
     */
    public function updatePreferences(Request $request)
    {
        $user = $request->user();
        
        $validated = $request->validate([
            'language_preference' => 'required|string|in:en,ne',
        ]);

        $user->update([
            'language_preference' => $validated['language_preference']
        ]);

        return response()->json([
            'message' => 'Preferences updated successfully',
            'user' => $user
        ]);
    }
}
