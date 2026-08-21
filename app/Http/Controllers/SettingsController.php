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

        User::create($validated);


        return redirect()->route('settings', ['tab' => 'user_role'])->with('success', 'Akun pengguna baru berhasil dibuat.');
    }

    /**
     * Delete a user account (Developer only).
     */
    public function destroy(Request $request, User $user)
    {
        $currentUser = $request->user();
        if (!$currentUser || !$currentUser->isDeveloper()) {
            abort(403, 'Unauthorized. Hanya Developer yang dapat menghapus akun pengguna.');
        }

        if ($currentUser->id === $user->id) {
            return redirect()->route('settings', ['tab' => 'user_role'])->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang digunakan.');
        }

        $userName = $user->name;
        $userEmail = $user->email;
        $user->delete();

        return redirect()->route('settings', ['tab' => 'user_role'])->with('success', "Akun pengguna {$userName} ({$userEmail}) berhasil dihapus.");
    }
}

