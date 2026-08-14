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

<aside class="cmms-sidebar fixed inset-y-6 left-6 z-30 flex flex-col transition-all duration-300 ease-in-out"
       :class="collapsed ? 'w-20 px-2' : 'w-72 px-0'">
    
    <!-- Top Header: Logo + Title + Collapse/Expand Toggle Button -->
    <div class="flex items-center justify-between px-4 pb-4 pt-5 border-b border-slate-200/50">
        <div class="flex items-center gap-3 overflow-hidden">
            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-2xl bg-[#4F7CFF] text-sm font-bold tracking-wide text-white shadow-[0_12px_28px_rgba(79,124,255,0.28)]">
                HC
            </div>
            <div x-show="!collapsed" x-transition.opacity class="min-w-0">
                <h1 class="text-[15px] font-semibold tracking-tight text-slate-900 truncate">Hospital CMMS</h1>
                <p class="text-[11px] text-slate-500 truncate">Maintenance workspace</p>
            </div>
        </div>

        <!-- Sidebar Collapse / Expand Toggle Button -->
        <button type="button"
                @click="collapsed = !collapsed; localStorage.setItem('cmms_sidebar_collapsed', collapsed)"
                class="flex h-8 w-8 items-center justify-center rounded-xl bg-white/80 text-slate-600 hover:bg-white hover:text-slate-900 shadow-sm border border-slate-200/70 transition flex-shrink-0 cursor-pointer"
                :title="collapsed ? 'Expand Sidebar' : 'Collapse Sidebar'">
            <svg viewBox="0 0 24 24" class="w-4 h-4 transition-transform duration-300" :class="{ 'rotate-180': collapsed }" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
        </button>
    </div>

    <!-- Scrollable Navigation Container -->
    <nav class="cmms-sidebar__nav flex-1 overflow-y-auto py-3 space-y-1.5 custom-scrollbar" aria-label="Primary navigation">
        @foreach ($navigation as $item)
            @if (isset($item['section']))
                <!-- Section Label Header (Expanded view) -->
                <p x-show="!collapsed" x-transition.opacity class="sidebar-label px-3 mt-4 mb-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                    {{ $item['section'] }}
                </p>
                <!-- Subtle divider line (Collapsed view) -->
                <div x-show="collapsed" class="my-2 border-t border-slate-200/60 mx-2"></div>
            @else
                <!-- Navigation Link Item -->
                <a href="{{ route($item['route']) }}"
                   class="sidebar-link flex items-center gap-3 rounded-2xl transition-all text-slate-600 hover:bg-white/80 hover:text-slate-900 {{ request()->routeIs($item['active']) ? 'sidebar-link--active font-semibold text-[#4F7CFF]' : '' }}"
                   :class="collapsed ? 'justify-center px-0 py-3 mx-auto w-11 h-11' : 'px-3.5 py-2.5 mx-3'"
                   :title="collapsed ? '{{ $item['label'] }}' : ''">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="w-5 h-5 flex-shrink-0" aria-hidden="true">
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
                    <span x-show="!collapsed" x-transition.opacity class="truncate text-sm font-medium">{{ $item['label'] }}</span>
                </a>
            @endif
        @endforeach
    </nav>

    <!-- User Profile Footer Card -->
    <div class="mx-2 mb-3 mt-2 rounded-2xl border border-white/70 bg-white/60 p-2.5 backdrop-blur-xl transition-all">
        <div class="sidebar-user-card flex items-center gap-3" :class="collapsed ? 'justify-center' : ''">
            <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-slate-900 text-sm font-semibold text-white">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div x-show="!collapsed" x-transition.opacity class="min-w-0">
                <p class="truncate text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                <p class="truncate text-xs text-slate-500">Hospital team</p>
            </div>
        </div>
        <p x-show="!collapsed" x-transition.opacity class="mt-2 text-[10px] font-medium uppercase tracking-[0.16em] text-slate-400 text-center">CMMS · {{ date('Y') }}</p>
    </div>
</aside>
