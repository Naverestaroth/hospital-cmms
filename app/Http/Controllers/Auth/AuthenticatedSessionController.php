<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        if ($user && $user->role === 'kepala_ipsrs') {
            $request->session()->forget('developer_mode');
            return redirect()->intended(route('dashboard', absolute: false));
        }

        if ($request->input('login_type') === 'developer' || $request->boolean('developer_mode')) {
            $request->session()->put('developer_mode', true);
            return redirect()->route('settings')->with('success', 'Logged in successfully under Developer Mode.');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Authenticate directly as Developer without requiring email and password.
     */
    public function developerQuickLogin(Request $request): RedirectResponse
    {
        $user = \App\Models\User::where('email', 'developer@hospital.com')->orWhere('role', 'developer')->first();
        if (! $user) {
            $user = \App\Models\User::create([
                'name' => 'Developer Admin',
                'email' => 'developer@hospital.com',
                'role' => 'developer',
                'password' => Hash::make('password'),
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('developer_mode', true);

        return redirect()->route('settings')->with('success', 'Logged in directly as Developer.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $email = $user ? $user->email : null;

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($email) {
            Cookie::queue('remembered_email', $email, 43200);
        }

        return redirect('/login');
    }
}

