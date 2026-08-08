@props([
    'title' => 'IPSRS Operations Dashboard',
    'subtitle' => null
])

<header class="glass max-w-[1500px] mx-auto rounded-[22px] px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-20">
    <div>
        <h1 class="disp text-2xl font-bold text-[#0B1E26] tracking-tight">{{ $title }}</h1>
        <p class="text-xs text-[#5B7480] font-medium mt-0.5">
            Welcome back, {{ Auth::user()->name }}
            @if($subtitle)
                • {{ $subtitle }}
            @else
                • Real-time equipment health & workflow summary
            @endif
        </p>
    </div>

    <div class="flex items-center gap-3">
        <div class="sync-pill inset-box px-3.5 py-1.5 rounded-full text-xs font-semibold text-[#0A4A57] flex items-center gap-2">
            <span class="beacon"></span>
            <span>Live CMMS System Sync</span>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn px-4 py-1.5 rounded-full text-xs font-semibold text-[#5B7480] hover:text-[#0B1E26] hover:bg-white/60 transition-all duration-200 border border-transparent hover:border-white/80">
                Logout
            </button>
        </form>
    </div>
</header>