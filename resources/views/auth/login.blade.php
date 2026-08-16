<x-guest-layout>

    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold tracking-tight text-slate-900">
            Welcome Back
        </h2>

        <p class="mt-3 text-slate-500">
            Sign in to access the IPSRS maintenance system.
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6" x-data="{ loginType: 'standard' }">
        @csrf

        <!-- Login Mode Switcher (Standard vs Developer Login) -->
        <div class="flex items-center rounded-2xl bg-slate-100 p-1 text-xs font-semibold shadow-inner">
            <button type="button" 
                    @click="loginType = 'standard'" 
                    :class="loginType === 'standard' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900'" 
                    class="flex-1 rounded-xl py-2.5 transition text-center font-bold">
                Standard Login
            </button>
            <button type="button" 
                    @click="loginType = 'developer'" 
                    :class="loginType === 'developer' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-500 hover:text-slate-900'" 
                    class="flex-1 rounded-xl py-2.5 transition text-center font-bold flex items-center justify-center gap-1.5">
                <svg viewBox="0 0 24 24" class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14.7 6.3a4 4 0 1 1-5.4 5.4L3 18v3h3l6.3-6.3"/>
                </svg>
                Developer Login
            </button>
        </div>

        <input type="hidden" name="login_type" :value="loginType">

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
                :value="old('email')"
                placeholder="developer@hospital.com"
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

        <!-- Remember & Developer Mode Checkbox -->
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

            <label class="flex items-center gap-2 cursor-pointer">
                <input 
                    type="checkbox" 
                    name="developer_mode" 
                    value="1" 
                    :checked="loginType === 'developer'" 
                    @change="loginType = $el.checked ? 'developer' : 'standard'" 
                    class="rounded border-slate-300 text-slate-900 focus:ring-slate-900">
                <span class="text-xs font-semibold text-slate-700">Developer Mode</span>
            </label>
        </div>

        <!-- Button -->
        <button
            type="submit"
            :class="loginType === 'developer' ? 'bg-slate-900 hover:bg-slate-800 focus:ring-slate-800' : 'bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-600'"
            class="w-full justify-center rounded-2xl py-3.5 text-base font-semibold text-white transition focus:outline-none focus:ring-2 focus:ring-offset-2 shadow-sm flex items-center justify-center gap-2">
            <template x-if="loginType === 'developer'">
                <svg viewBox="0 0 24 24" class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14.7 6.3a4 4 0 1 1-5.4 5.4L3 18v3h3l6.3-6.3"/>
                </svg>
            </template>
            <span x-text="loginType === 'developer' ? 'Sign In (Developer Mode)' : 'Sign In'"></span>
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