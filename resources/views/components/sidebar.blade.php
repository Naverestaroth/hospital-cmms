<aside class="fixed left-0 top-0 flex h-screen w-64 flex-col border-r border-slate-200 bg-white">

    <!-- Logo -->
    <div class="flex h-24 items-center border-b border-slate-200 px-8">

        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-600 text-xl font-bold text-white shadow">
            CM
        </div>

        <div class="ml-4">
            <h1 class="text-xl font-bold text-slate-900">
                Hospital CMMS
            </h1>

            <p class="text-sm text-slate-500">
                IPSRS
            </p>
        </div>

    </div>

    <!-- Menu -->
    <nav class="flex-1 space-y-2 p-4">

        <a href="{{ route('dashboard') }}"
            class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('dashboard') ? 'bg-emerald-50 font-semibold text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">
            Dashboard
        </a>

        <a href="{{ route('assets.index') }}"
            class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('assets.*') ? 'bg-emerald-50 font-semibold text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">
            Assets
        </a>

        <a href="{{ route('tickets.index') }}"
            class="block rounded-2xl px-4 py-3 transition
            {{ request()->routeIs('tickets.*') ? 'bg-emerald-50 font-semibold text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">
            Tickets
        </a>

        <a href="{{ route('preventives.index') }}"

            class="block rounded-2xl px-4 py-3 transition

            {{ request()->routeIs('preventives.*')

                ? 'bg-emerald-50 font-semibold text-emerald-700'

                : 'text-slate-600 hover:bg-slate-100' }}">

            Preventive

        </a>

        <a href="{{ route('correctives.index') }}"
            class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('correctives.*') ? 'bg-emerald-50 font-semibold text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">
            Corrective
        </a>

        <a href="{{ route('history') }}"
            class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('history') ? 'bg-emerald-50 font-semibold text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">
            Maintenance History
        </a>

        <a href="{{ route('vendors.index') }}"
            class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('vendors.*') ? 'bg-emerald-50 font-semibold text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">
            Vendors
        </a>

        <a href="{{ route('spareparts.index') }}"
            class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('spareparts.*') ? 'bg-emerald-50 font-semibold text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">
            Spareparts
        </a>

        <a href="{{ route('documents.index') }}"
            class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('documents.*') ? 'bg-emerald-50 font-semibold text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">
            document Center
        </a>

        <a href="{{ route('reports') }}"
            class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('reports') ? 'bg-emerald-50 font-semibold text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">
            Reports
        </a>

        <a href="{{ route('settings') }}"
            class="block rounded-2xl px-4 py-3 transition {{ request()->routeIs('settings') ? 'bg-emerald-50 font-semibold text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">
            Settings
        </a>

    </nav>

</aside>