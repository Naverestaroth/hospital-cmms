<x-guest-layout>

    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold tracking-tight text-slate-900">
            Welcome Back
        </h2>

        <p class="mt-3 text-slate-500">
            Sign in to access the IPSRS maintenance system.
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label
                for="email"
                value="Email Address"
                class="text-slate-700 font-medium"
            />

            <x-text-input
                id="email"
                type="email"
                name="email"
                :value="old('email', request()->cookie('remembered_email'))"
                placeholder="kepala.ipsrs@hospital.com"
                required
                autofocus
                autocomplete="username"
                class="mt-2 block w-full rounded-2xl border-slate-300 py-3 px-4 focus:border-emerald-500 focus:ring-emerald-500"
            />


            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />
        </div>

        <!-- Password -->
        <div>

            <div class="flex items-center justify-between">

                <x-input-label
                    for="password"
                    value="Password"
                    class="text-slate-700 font-medium"
                />

                @if (Route::has('password.request'))
                    <a
                        href="{{ route('password.request') }}"
                        class="text-sm font-medium text-emerald-600 hover:text-emerald-700 transition">
                        Forgot Password?
                    </a>
                @endif

            </div>

            <x-text-input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                class="mt-2 block w-full rounded-2xl border-slate-300 py-3 px-4 focus:border-emerald-500 focus:ring-emerald-500"
            />

            <x-input-error
                :messages="$errors->get('password')"
                class="mt-2"
            />

        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center justify-between gap-4">
            <label class="flex items-center gap-2.5">
                <input
                    type="checkbox"
                    name="remember"
                    class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <span class="text-sm text-slate-600 font-medium">
                    Remember me
                </span>
            </label>
        </div>

        <!-- Button -->
        <button
            type="submit"
            class="w-full justify-center rounded-2xl bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-600 py-3.5 text-base font-semibold text-white transition focus:outline-none focus:ring-2 focus:ring-offset-2 shadow-sm flex items-center justify-center gap-2">
            <span>Sign In</span>
        </button>

    </form>

    <!-- Quick Developer Direct Access (1-Click Login Without Email & Password) -->
    <div class="mt-6 pt-6 border-t border-slate-200/80 text-center">
        <form method="POST" action="{{ route('login.developer-quick') }}">
            @csrf
            <button
                type="submit"
                class="w-full rounded-2xl border border-amber-300 bg-amber-50/80 hover:bg-amber-100 text-amber-900 px-4 py-3 text-xs font-bold transition shadow-sm flex items-center justify-center gap-2 cursor-pointer">
                <svg viewBox="0 0 24 24" class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/>
                </svg>
                <span>⚡ Masuk Langsung Sebagai Developer (Bypass Email & Password)</span>
            </button>
        </form>
    </div>

</x-guest-layout>