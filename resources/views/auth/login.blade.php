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
                :value="old('email')"
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

        <!-- Remember -->

        <label class="flex items-center gap-3">

            <input
                type="checkbox"
                name="remember"
                class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">

            <span class="text-sm text-slate-600">
                Remember me
            </span>

        </label>

        <!-- Button -->

        <x-primary-button
            class="w-full justify-center rounded-2xl bg-emerald-600 py-3.5 text-base font-semibold transition hover:bg-emerald-700">

            Sign In

        </x-primary-button>

    </form>

</x-guest-layout>