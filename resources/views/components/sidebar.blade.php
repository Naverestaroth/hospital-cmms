@php
    $navigation = [
        ['label' => 'Dashboard', 'route' => 'dashboard', 'active' => 'dashboard', 'icon' => 'dashboard'],
        ['section' => 'Asset management'],
        ['label' => 'Assets', 'route' => 'assets.index', 'active' => 'assets.*', 'icon' => 'asset'],
        ['label' => 'Vendors', 'route' => 'vendors.index', 'active' => 'vendors.*', 'icon' => 'building'],
        ['label' => 'Spareparts', 'route' => 'spareparts.index', 'active' => 'spareparts.*', 'icon' => 'tool'],
        ['label' => 'Document Center', 'route' => 'documents.index', 'active' => 'documents.*', 'icon' => 'document'],
        ['section' => 'Maintenance'],
        ['label' => 'Tickets', 'route' => 'tickets.index', 'active' => 'tickets.index', 'icon' => 'ticket'],
        ['label' => 'Equipment Movements', 'route' => 'tickets.movements', 'active' => 'tickets.movements', 'icon' => 'arrow-swap'],
        ['label' => 'Preventive', 'route' => 'preventives.index', 'active' => 'preventives.*', 'icon' => 'clock'],
        ['label' => 'Corrective', 'route' => 'correctives.index', 'active' => 'correctives.*', 'icon' => 'tool'],
        ['label' => 'Maintenance History', 'route' => 'history', 'active' => 'history', 'icon' => 'clock'],
        ['label' => 'Technicians', 'route' => 'technicians.index', 'active' => 'technicians.*', 'icon' => 'technician'],
        ['section' => 'System'],
        ['label' => 'Reports', 'route' => 'reports', 'active' => 'reports', 'icon' => 'chart'],
        ['label' => 'Settings', 'route' => 'settings', 'active' => 'settings', 'icon' => 'settings'],
    ];
@endphp

<aside class="cmms-sidebar fixed inset-y-6 left-6 z-30 flex w-72 flex-col">
    <div class="flex items-center gap-3 px-5 pb-6 pt-5">
        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#4F7CFF] text-sm font-bold tracking-wide text-white shadow-[0_12px_28px_rgba(79,124,255,0.28)]">
            HC
        </div>
        <div>
            <h1 class="text-[15px] font-semibold tracking-tight text-slate-900">Hospital CMMS</h1>
            <p class="mt-0.5 text-xs text-slate-500">Maintenance workspace</p>
        </div>
    </div>

    <nav class="cmms-sidebar__nav" aria-label="Primary navigation">
        @foreach ($navigation as $item)
            @if (isset($item['section']))
                <p class="sidebar-label">{{ $item['section'] }}</p>
            @else
                <a href="{{ route($item['route']) }}" class="sidebar-link {{ request()->routeIs($item['active']) ? 'sidebar-link--active' : '' }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        @if ($item['icon'] === 'dashboard')
                            <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>
                        @elseif ($item['icon'] === 'asset')
                            <path d="m4 7 8-4 8 4-8 4-8-4Z"/><path d="m4 12 8 4 8-4"/><path d="m4 17 8 4 8-4"/>
                        @elseif ($item['icon'] === 'building')
                            <path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-6h6v6"/><path d="M9 9h.01M15 9h.01"/>
                        @elseif ($item['icon'] === 'document')
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M8 13h8M8 17h6"/>
                        @elseif ($item['icon'] === 'ticket')
                            <path d="M5 3h14a2 2 0 0 1 2 2v5a2 2 0 0 0 0 4v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5a2 2 0 0 0 0-4V5a2 2 0 0 1 2-2Z"/><path d="M13 5v2M13 17v2"/>
                        @elseif ($item['icon'] === 'arrow-swap')
                            <path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        @elseif ($item['icon'] === 'clock')
                            <circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>
                        @elseif ($item['icon'] === 'technician')
                            <path d="M12 11a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm6 9v-1a4 4 0 0 0-4-4H10a4 4 0 0 0-4 4v1"/>
                            <path d="M4 21v-2a6 6 0 0 1 6-6h4a6 6 0 0 1 6 6v2"/>
                        @elseif ($item['icon'] === 'chart')
                            <path d="M3 3v18h18"/><path d="m7 16 4-5 3 3 5-7"/>
                        @elseif ($item['icon'] === 'settings')
                            <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06-2.48 2.48-.06-.06a1.7 1.7 0 0 0-1.88-.34 1.7 1.7 0 0 0-1.04 1.55v.1h-3.5v-.1a1.7 1.7 0 0 0-1.04-1.55 1.7 1.7 0 0 0-1.88.34l-.06.06-2.48-2.48.06-.06A1.7 1.7 0 0 0 5.78 15a1.7 1.7 0 0 0-1.55-1.04h-.1v-3.5h.1A1.7 1.7 0 0 0 5.78 9.4a1.7 1.7 0 0 0-.34-1.88l-.06-.06 2.48-2.48.06.06a1.7 1.7 0 0 0 1.88.34 1.7 1.7 0 0 0 1.04-1.55v-.1h3.5v.1A1.7 1.7 0 0 0 15.38 5.4a1.7 1.7 0 0 0 1.88-.34l.06-.06 2.48 2.48-.06.06a1.7 1.7 0 0 0-.34 1.88 1.7 1.7 0 0 0 1.55 1.04h.1v3.5h-.1A1.7 1.7 0 0 0 19.4 15Z"/>
                        @else
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l2.7-2.7a6 6 0 0 1-7.9 7.9l-6.8 6.8a2.1 2.1 0 0 1-3-3l6.8-6.8a6 6 0 0 1 7.9-7.9l-2.7 2.7Z"/>
                        @endif
                    </svg>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endif
        @endforeach
    </nav>

    <div class="mx-3 mb-3 mt-4 rounded-2xl border border-white/70 bg-white/60 p-3 backdrop-blur-xl">
        <div class="sidebar-user-card flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-900 text-sm font-semibold text-white">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="truncate text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                <p class="truncate text-xs text-slate-500">Hospital team</p>
            </div>
        </div>
        <p class="mt-3 text-[10px] font-medium uppercase tracking-[0.16em] text-slate-400">CMMS · {{ date('Y') }}</p>
    </div>
</aside>
