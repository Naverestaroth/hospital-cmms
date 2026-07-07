<header class="flex h-20 items-center justify-between border-b border-slate-200 bg-white px-8">

    <div>

        <h2 class="text-2xl font-bold text-slate-900">
            Dashboard
        </h2>

        <p class="text-sm text-slate-500">
            Welcome back, {{ Auth::user()->name }}
        </p>

    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button
            type="submit"
            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-slate-700 transition hover:bg-slate-100">

            Logout
        </button>

    </form>

</header>