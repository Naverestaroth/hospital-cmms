<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    /**
     * Store a newly created user account.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if (!($user->isKepalaIpsrs() || $user->isDeveloper())) {
            abort(403, 'Unauthorized to create user accounts.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,kepala_ipsrs,teknisi,developer',

            'google_email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
        ]);

        // Hash the password (model casts also hash, but ensure)
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('settings', ['tab' => 'user_role'])->with('success', 'Akun pengguna baru berhasil dibuat.');
    }
}
