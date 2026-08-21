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
        @php
            $unreadCount = 0;
            $allNotifications = collect();
            if (Auth::check() && \Illuminate\Support\Facades\Schema::hasTable('notifications')) {
                try {
                    $unreadNotifications = Auth::user()->unreadNotifications;
                    $allNotifications = Auth::user()->notifications()->take(10)->get();
                    $unreadCount = $unreadNotifications->count();
                } catch (\Throwable $e) {
                    // Fallback safely if notifications table is not present
                }
            }
        @endphp


        <!-- Notification Bell Dropdown Component -->
        <div x-data="{ openNotif: false }" class="relative z-30">
            <button @click="openNotif = !openNotif" 
                    @click.away="openNotif = false"
                    type="button" 
                    class="relative p-2 rounded-full text-slate-600 hover:text-slate-900 hover:bg-white/70 transition border border-transparent hover:border-slate-200 focus:outline-none cursor-pointer"
                    title="Notifikasi Sistem">
                <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>

                @if($unreadCount > 0)
                    <span class="absolute top-0 right-0 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white shadow-sm ring-2 ring-white animate-pulse">
                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                    </span>
                @endif
            </button>

            <!-- Dropdown Menu -->
            <div x-show="openNotif" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-80 sm:w-96 rounded-3xl bg-white shadow-2xl border border-slate-100 overflow-hidden z-50">
                
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm font-bold text-slate-800">Notifikasi</h3>
                        @if($unreadCount > 0)
                            <span class="rounded-full bg-red-100 px-2 py-0.5 text-[11px] font-bold text-red-700">{{ $unreadCount }} Baru</span>
                        @endif
                    </div>
                    @if($unreadCount > 0)
                        <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                            @csrf
                            <button type="submit" class="text-[11px] font-semibold text-emerald-600 hover:text-emerald-800 hover:underline cursor-pointer">
                                Tandai Semua Dibaca
                            </button>
                        </form>
                    @endif
                </div>

                <div class="max-h-80 overflow-y-auto divide-y divide-slate-100 text-xs custom-scrollbar">
                    @forelse($allNotifications as $n)
                        @php
                            $isUnread = is_null($n->read_at);
                            $data = $n->data;
                        @endphp
                        <a href="{{ route('notifications.read', $n->id) }}" 
                           class="block p-3.5 transition hover:bg-slate-50 {{ $isUnread ? 'bg-emerald-50/40' : '' }}">
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 rounded-xl p-2 {{ $isUnread ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                    @if(($data['icon'] ?? '') === 'check-circle')
                                        <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                                        </svg>
                                    @else
                                        <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0 space-y-1">
                                    <div class="flex items-center justify-between gap-1">
                                        <p class="font-bold {{ $isUnread ? 'text-slate-900' : 'text-slate-700' }} truncate">
                                            {{ $data['title'] ?? 'Notifikasi Tiket' }}
                                        </p>
                                        @if($isUnread)
                                            <span class="h-2 w-2 rounded-full bg-emerald-500 flex-shrink-0"></span>
                                        @endif
                                    </div>
                                    <p class="text-slate-600 line-clamp-2 leading-relaxed">
                                        {{ $data['message'] ?? '' }}
                                    </p>
                                    <p class="text-[10px] font-medium text-slate-400">
                                        {{ $n->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="py-8 text-center text-slate-400 space-y-1">
                            <svg viewBox="0 0 24 24" class="w-8 h-8 mx-auto text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                            </svg>
                            <p class="font-medium text-xs">Belum ada notifikasi saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

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