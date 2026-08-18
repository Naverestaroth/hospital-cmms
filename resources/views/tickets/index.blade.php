<x-app-layout>

    <div class="space-y-6" x-data="{ viewMode: '{{ request('view', 'default') }}' }">

        @if(session('success'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-red-800">
            {{ session('error') }}
        </div>
        @endif

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">
                    Ticket Management
                </h1>
                <p class="mt-2 text-slate-500">
                    Manage hospital service tickets, approvals, technician assignments, and progress.
                </p>
            </div>

            <a href="{{ route('tickets.create') }}" class="ds-button-primary">
                + New Ticket
            </a>
        </div>

        <!-- Filter Tabs & Search -->
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">

            <!-- Workflow Filter Badges -->
            <div class="flex flex-wrap gap-2 text-xs border-b border-slate-100 pb-4">
                <a href="{{ route('tickets.index') }}"
                   class="px-3 py-1.5 rounded-xl font-medium transition {{ !request('status') ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    All ({{ $statusCounts['all'] }})
                </a>
                <a href="{{ route('tickets.index', ['status' => 'Waiting Approval']) }}"
                   class="px-3 py-1.5 rounded-xl font-medium transition {{ request('status') === 'Waiting Approval' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                    Waiting Approval ({{ $statusCounts['waiting_approval'] }})
                </a>
                <a href="{{ route('tickets.index', ['status' => 'Open']) }}"
                   class="px-3 py-1.5 rounded-xl font-medium transition {{ request('status') === 'Open' ? 'bg-blue-600 text-white' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                    Open ({{ $statusCounts['open'] }})
                </a>
                <a href="{{ route('tickets.index', ['status' => 'Assigned']) }}"
                   class="px-3 py-1.5 rounded-xl font-medium transition {{ request('status') === 'Assigned' ? 'bg-purple-600 text-white' : 'bg-purple-50 text-purple-700 hover:bg-purple-100' }}">
                    Assigned ({{ $statusCounts['assigned'] }})
                </a>
                <a href="{{ route('tickets.index', ['status' => 'In Progress']) }}"
                   class="px-3 py-1.5 rounded-xl font-medium transition {{ request('status') === 'In Progress' ? 'bg-indigo-600 text-white' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100' }}">
                    In Progress ({{ $statusCounts['in_progress'] }})
                </a>
                <a href="{{ route('tickets.index', ['status' => 'Repair Completed']) }}"
                   class="px-3 py-1.5 rounded-xl font-medium transition {{ request('status') === 'Repair Completed' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                    Completed ({{ $statusCounts['completed'] }})
                </a>
                <a href="{{ route('tickets.index', ['status' => 'Closed']) }}"
                   class="px-3 py-1.5 rounded-xl font-medium transition {{ request('status') === 'Closed' ? 'bg-slate-700 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    Closed ({{ $statusCounts['closed'] }})
                </a>
            </div>

            <!-- Search + View Mode Switcher -->
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <form action="{{ route('tickets.index') }}" method="GET" class="flex-1 flex gap-4">
                    @if(request('status'))
                        <input type="hidden" name="status" value="{{ request('status') }}">
                    @endif
                    <input type="hidden" name="view" :value="viewMode">

                    <input
                        type="text"
                        name="search"
                        placeholder="Search ticket ID, asset, room, reported by, technician..."
                        class="flex-1 rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-emerald-500 focus:outline-none"
                        value="{{ request('search') }}">

                    <button type="submit" class="rounded-xl border border-slate-200 px-5 text-sm font-medium hover:bg-slate-100">
                        Search
                    </button>

                    <a href="{{ route('tickets.index') }}" class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm hover:bg-slate-100">
                        Reset
                    </a>
                </form>

                <!-- View Mode Switcher -->
                <div class="flex items-center gap-1.5 rounded-2xl border border-slate-200 bg-slate-50 p-1.5 self-start md:self-auto">
                    <button
                        type="button"
                        @click="viewMode = 'default'"
                        :class="viewMode === 'default' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/80' : 'text-slate-600 hover:bg-slate-200/60'"
                        class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold transition">
                        <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18"/>
                        </svg>
                        Default
                    </button>

                    <button
                        type="button"
                        @click="viewMode = 'room'"
                        :class="viewMode === 'room' ? 'bg-white text-slate-900 shadow-sm border border-slate-200/80' : 'text-slate-600 hover:bg-slate-200/60'"
                        class="flex items-center gap-2 rounded-xl px-4 py-2.5 text-xs font-bold transition">
                        <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 21h18M3 7v14M21 7v14M6 21V3h12v18M9 6h2M9 10h2M9 14h2M13 6h2M13 10h2M13 14h2"/>
                        </svg>
                        Per Ruangan
                    </button>
                </div>
            </div>
        </div>

        <!-- =================== DEFAULT VIEW =================== -->
        <div x-show="viewMode === 'default'">
            <div class="overflow-x-auto rounded-3xl border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full">
                    <thead class="bg-slate-50">
                        @php
                            if (!function_exists('sortUrl')) {
                                function sortUrl($field) {
                                    return request()->fullUrlWithQuery([
                                        'sort'      => $field,
                                        'direction' => request('sort') === $field && request('direction') === 'asc' ? 'desc' : 'asc'
                                    ]);
                                }
                            }
                        @endphp
                        <tr class="border-t transition hover:bg-slate-50 text-xs font-semibold text-slate-600 uppercase">
                            <th class="px-6 py-4 text-left">No</th>
                            <th class="px-6 py-4 text-left"><a href="{{ sortUrl('ticket_code') }}">Ticket ID</a></th>
                            <th class="px-6 py-4 text-left">Room</th>
                            <th class="px-6 py-4 text-left">Asset Name</th>
                            <th class="px-6 py-4 text-left"><a href="{{ sortUrl('reported_by') }}">Reported By</a></th>
                            <th class="px-6 py-4 text-left">Technician(s)</th>
                            <th class="px-6 py-4 text-left"><a href="{{ sortUrl('priority') }}">Priority</a></th>
                            <th class="px-6 py-4 text-left"><a href="{{ sortUrl('status') }}">Status</a></th>
                            <th class="px-6 py-4 text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($tickets as $ticket)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $tickets->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900">
                                <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600 hover:underline">{{ $ticket->ticket_code }}</a>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-700">{{ $ticket->room ?? $ticket->asset?->room ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-800">{{ $ticket->asset?->asset_name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-slate-700">
                                {{ $ticket->reported_by }}
                                <span class="block text-xs text-slate-400">({{ $ticket->creator_type ?? 'User' }})</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($ticket->technicians->count() > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($ticket->technicians as $tech)
                                            <span class="inline-flex items-center rounded-lg bg-slate-100 border border-slate-200 px-2 py-0.5 text-xs font-medium text-slate-700">{{ $tech->name }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 italic">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($ticket->priority === 'High')
                                    <span class="inline-flex items-center rounded-full bg-red-50 border border-red-200 px-2.5 py-0.5 text-xs font-medium text-red-700">High</span>
                                @elseif($ticket->priority === 'Medium')
                                    <span class="inline-flex items-center rounded-full bg-amber-50 border border-amber-200 px-2.5 py-0.5 text-xs font-medium text-amber-700">Medium</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-100 border border-slate-200 px-2.5 py-0.5 text-xs font-medium text-slate-700">Low</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @php
                                    $statusClasses = match($ticket->status) {
                                        'Waiting Approval' => 'bg-amber-50 text-amber-800 border-amber-200',
                                        'Approved', 'Open' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'Assigned', 'Accepted' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'In Progress' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'Waiting Sparepart', 'Waiting Vendor', 'Waiting User' => 'bg-amber-100 text-amber-900 border-amber-300',
                                        'Repair Completed', 'Waiting Corrective Report', 'Corrective Report Completed' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                        'Closed' => 'bg-slate-100 text-slate-700 border-slate-300',
                                        'Rejected', 'Cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium {{ $statusClasses }}">
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="text-xs font-semibold text-blue-600 hover:underline">View</a>
                                    <a href="{{ route('tickets.edit', $ticket) }}" class="text-xs font-semibold text-emerald-700 hover:underline">Edit</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="py-10 text-center text-sm text-slate-500">No ticket data available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- =================== PER RUANGAN VIEW =================== -->
        @php
            $itemsToGroup  = method_exists($tickets, 'getCollection') ? $tickets->getCollection() : $tickets;
            $groupedItems  = $itemsToGroup->groupBy(function ($item) {
                $room = $item->room ?? $item->asset?->room ?? null;
                return !empty(trim((string)$room)) ? trim((string)$room) : 'Unassigned / Ruangan Tidak Ditentukan';
            })->sortKeys();
            $firstRoomSlug = $groupedItems->keys()->isNotEmpty() ? Str::slug($groupedItems->keys()->first()) : '';
        @endphp

        <div x-show="viewMode === 'room'" class="space-y-4" x-data="{
            activeRoom: window.location.hash.startsWith('#room-') ? window.location.hash.substring(6) : '{{ $firstRoomSlug }}',
            canScrollLeft: false,
            canScrollRight: false,
            checkScroll() {
                const el = this.$refs.scrollContainer;
                if (!el) return;
                const tolerance = 2;
                this.canScrollLeft  = el.scrollLeft > tolerance;
                this.canScrollRight = el.scrollLeft + el.clientWidth < el.scrollWidth - tolerance;
            }
        }" x-init="
            $nextTick(() => checkScroll());
            window.addEventListener('resize', () => checkScroll());
            window.addEventListener('hashchange', () => {
                if (window.location.hash.startsWith('#room-')) {
                    activeRoom = window.location.hash.substring(6);
                }
            });
        ">

            <!-- Liquid Glass Room Navigation -->
            <div class="relative w-full overflow-hidden py-1 mb-1">
                <div class="mx-auto max-w-[1500px]">
                    <div class="relative overflow-hidden rounded-[40px] border border-white bg-white/35 shadow-[0_12px_30px_rgba(15,23,42,0.04)] backdrop-blur-[30px] ring-1 ring-black/5">

                        <div class="absolute inset-0 bg-gradient-to-r from-white/60 via-white/20 to-white/5 opacity-70 pointer-events-none"></div>
                        <div class="absolute inset-0 bg-gradient-to-b from-white/40 to-transparent opacity-50 pointer-events-none"></div>

                        <div x-show="canScrollLeft" x-transition.opacity.duration.300ms class="absolute left-0 top-0 bottom-0 w-16 bg-gradient-to-r from-white/90 via-white/70 to-transparent z-10 pointer-events-none rounded-l-[40px]"></div>
                        <div x-show="canScrollRight" x-transition.opacity.duration.300ms class="absolute right-0 top-0 bottom-0 w-16 bg-gradient-to-l from-white/90 via-white/70 to-transparent z-10 pointer-events-none rounded-r-[40px]"></div>

                        <div x-ref="scrollContainer" @scroll.passive="checkScroll" class="relative flex items-center gap-2 sm:gap-3 px-4 py-2 overflow-x-auto scroll-smooth z-0 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">

                            @forelse($roomPages ?? [] as $roomName => $pageNum)
                                @php $roomSlug = Str::slug($roomName); @endphp
                                <a
                                    href="{{ request()->fullUrlWithQuery(['page' => $pageNum, 'view' => 'room']) }}#room-{{ $roomSlug }}"
                                    class="relative whitespace-nowrap px-4 py-1.5 rounded-full text-[14px] sm:text-[15px] lg:text-[16px] font-medium tracking-tight transition-all duration-300 outline-none select-none flex-shrink-0"
                                    :class="activeRoom === '{{ $roomSlug }}' ? 'text-slate-900 shadow-sm ring-1 ring-black/5' : 'text-slate-600 hover:text-slate-900 hover:bg-white/40'"
                                    @click="activeRoom = '{{ $roomSlug }}'">

                                    <div x-show="activeRoom === '{{ $roomSlug }}'"
                                         x-transition.opacity
                                         class="absolute inset-0 bg-white/95 rounded-full shadow-[inset_0_1px_3px_rgba(255,255,255,1)] pointer-events-none -z-10"></div>

                                    {{ $roomName }}
                                </a>
                            @empty
                                <div class="px-5 py-2 text-slate-500 text-sm">No rooms available</div>
                            @endforelse

                        </div>
                    </div>
                </div>
            </div>

            <!-- Room Accordions -->
            @forelse($groupedItems as $roomName => $roomTickets)
                <div id="room-{{ Str::slug($roomName) }}" x-data="{ isOpen: true }" class="relative rounded-[28px] border border-white/[0.35] bg-white/[0.08] shadow-[0_12px_28px_rgba(15,23,42,0.06)] backdrop-blur-[30px] overflow-hidden transition scroll-mt-24">

                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-50 pointer-events-none"></div>

                    <!-- Room Accordion Header -->
                    <button
                        type="button"
                        @click="isOpen = !isOpen"
                        class="relative z-10 w-full flex items-center justify-between px-6 py-5 bg-white/[0.02] hover:bg-white/[0.06] transition text-left border-b border-white/[0.15]">

                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center">
                                <svg viewBox="0 0 24 24" class="w-5 h-5 text-blue-700" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 21h18M3 7v14M21 7v14M6 21V3h12v18M9 6h2M9 10h2M9 14h2M13 6h2M13 10h2M13 14h2"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-slate-900">{{ $roomName }}</h2>
                                <p class="text-xs text-slate-500">Service Tickets</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="rounded-full border border-blue-200 bg-blue-50 px-3.5 py-1 text-xs font-bold text-blue-700 shadow-sm">
                                {{ $roomTickets->count() }} {{ Str::plural('Ticket', $roomTickets->count()) }}
                            </span>
                            <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center shadow-sm" :class="{ 'rotate-180': isOpen }">
                                <svg viewBox="0 0 24 24" class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 9l6 6 6-6"/>
                                </svg>
                            </div>
                        </div>

                    </button>

                    <!-- Room Table -->
                    <div x-show="isOpen" class="relative z-10 overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-white/[0.04] text-xs font-semibold text-slate-700 uppercase tracking-wider border-b border-white/[0.15]">
                                <tr>
                                    <th class="px-6 py-3.5 text-left">No</th>
                                    <th class="px-6 py-3.5 text-left">Ticket ID</th>
                                    <th class="px-6 py-3.5 text-left">Asset Name</th>
                                    <th class="px-6 py-3.5 text-left">Reported By</th>
                                    <th class="px-6 py-3.5 text-left">Technician(s)</th>
                                    <th class="px-6 py-3.5 text-left">Priority</th>
                                    <th class="px-6 py-3.5 text-left">Status</th>
                                    <th class="px-6 py-3.5 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @foreach($roomTickets as $index => $ticket)
                                    <tr class="bg-white hover:bg-slate-50 transition text-slate-900">
                                        <td class="px-6 py-4 text-slate-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 font-semibold">
                                            <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600 hover:underline">{{ $ticket->ticket_code }}</a>
                                        </td>
                                        <td class="px-6 py-4 font-medium text-slate-800">{{ $ticket->asset?->asset_name ?? '—' }}</td>
                                        <td class="px-6 py-4 text-slate-700">
                                            {{ $ticket->reported_by }}
                                            <span class="block text-xs text-slate-400">({{ $ticket->creator_type ?? 'User' }})</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($ticket->technicians->count() > 0)
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($ticket->technicians as $tech)
                                                        <span class="inline-flex items-center rounded-lg bg-slate-100 border border-slate-200 px-2 py-0.5 text-xs font-medium text-slate-700">{{ $tech->name }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-xs text-slate-400 italic">Unassigned</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($ticket->priority === 'High')
                                                <span class="inline-flex items-center rounded-full bg-red-50 border border-red-200 px-2.5 py-0.5 text-xs font-medium text-red-700">High</span>
                                            @elseif($ticket->priority === 'Medium')
                                                <span class="inline-flex items-center rounded-full bg-amber-50 border border-amber-200 px-2.5 py-0.5 text-xs font-medium text-amber-700">Medium</span>
                                            @else
                                                <span class="inline-flex items-center rounded-full bg-slate-100 border border-slate-200 px-2.5 py-0.5 text-xs font-medium text-slate-700">Low</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $sc = match($ticket->status) {
                                                    'Waiting Approval' => 'bg-amber-50 text-amber-800 border-amber-200',
                                                    'Approved', 'Open' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                    'Assigned', 'Accepted' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                    'In Progress' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                    'Waiting Sparepart', 'Waiting Vendor', 'Waiting User' => 'bg-amber-100 text-amber-900 border-amber-300',
                                                    'Repair Completed', 'Waiting Corrective Report', 'Corrective Report Completed' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                                    'Closed' => 'bg-slate-100 text-slate-700 border-slate-300',
                                                    'Rejected', 'Cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                                    default => 'bg-slate-100 text-slate-700 border-slate-200',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium {{ $sc }}">
                                                {{ $ticket->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-3">
                                                <a href="{{ route('tickets.show', $ticket) }}" class="text-xs font-semibold text-blue-600 hover:underline">View</a>
                                                <a href="{{ route('tickets.edit', $ticket) }}" class="text-xs font-semibold text-emerald-700 hover:underline">Edit</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            @empty
                <div class="rounded-3xl border border-slate-200 bg-white p-10 text-center text-slate-500 shadow-sm">
                    No ticket data available for grouping.
                </div>
            @endforelse

        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $tickets->links() }}
        </div>

    </div>

</x-app-layout>