<x-app-layout>

    <div class="space-y-6" x-data="{ viewMode: '{{ request('view', 'default') }}' }">

        @if(session('success'))
        <div class="rounded-xl bg-green-100 p-4 text-green-700">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="rounded-xl bg-red-100 p-4 text-red-700">
            {{ session('error') }}
        </div>
        @endif

        <div class="flex items-center justify-between">

            <div>
                <h1 class="text-3xl font-bold text-slate-900">
                    Corrective Maintenance Management
                </h1>

                <p class="mt-2 text-slate-500">
                    Manage corrective maintenance.
                </p>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('correctives.import.upload') }}" class="rounded-2xl border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-100">
                    Import
                </a>

                <a
                    href="{{ route('correctives.create') }}"
                    class="ds-button-primary">
                    + New Corrective
                </a>
            </div>

        </div>

        <!-- Search & View Mode Switcher -->
        <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <form action="{{ route('correctives.index') }}" method="GET" class="flex-1 flex gap-4">
                <input type="hidden" name="view" :value="viewMode">

                <input
                    type="text"
                    name="search"
                    placeholder="Search corrective..."
                    class="flex-1 rounded-xl border border-slate-200 px-4 py-3 focus:border-emerald-500 focus:outline-none"
                    value="{{ request('search') }}">

                <button
                    type="submit"
                    class="rounded-xl border border-slate-200 px-5 hover:bg-slate-100">
                    Search
                </button>

                <a href="{{ route('correctives.index') }}" class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm hover:bg-slate-100">Reset</a>
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

        <!-- =================== DEFAULT VIEW =================== -->
        <div x-show="viewMode === 'default'">
            <x-table class="rounded-3xl border border-slate-200">
                @php
                    if (!function_exists('sortUrl')) {
                        function sortUrl($field) {
                            return request()->fullUrlWithQuery([
                                'sort'      => $field,
                                'direction' => request('sort') === $field && request('direction') === 'asc' ? 'desc' : 'asc',
                            ]);
                        }
                    }
                @endphp
                <x-slot name="thead">
                    <tr class="border-t transition hover:bg-slate-50">
                        <th class="px-6 py-4 text-left">No</th>
                        <th class="px-6 py-4 text-left"><a href="{{ sortUrl('repair_date') }}">Tanggal</a></th>
                        <th class="px-6 py-4 text-left"><a href="{{ sortUrl('distributor') }}">Distributor</a></th>
                        <th class="px-6 py-4 text-left">Pemeriksaan</th>
                        <th class="px-6 py-4 text-left"><a href="{{ sortUrl('problem') }}">Problem / Diagnosa</a></th>
                        <th class="px-6 py-4 text-left"><a href="{{ sortUrl('solution') }}">Solution</a></th>
                        <th class="px-6 py-4 text-left"><a href="{{ sortUrl('sparepart') }}">Sparepart</a></th>
                        <th class="px-6 py-4 text-left"><a href="{{ sortUrl('quantity') }}">Jumlah Sparepart</a></th>
                        <th class="px-6 py-4 text-left"><a href="{{ sortUrl('inspection_result') }}">Hasil Pemeriksaan</a></th>
                        <th class="px-6 py-4 text-left">Teknisi</th>
                        <th class="px-6 py-4 text-left"><a href="{{ sortUrl('user_name') }}">User</a></th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </x-slot>

                @forelse($correctives as $corrective)
                    <tr class="border-t">
                        <td class="px-6 py-4">{{ $correctives->firstItem() + $loop->index }}</td>
                        <td class="px-6 py-4">{{ $corrective->repair_date ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $corrective->distributor ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @php $inspections = is_array($corrective->inspection) ? $corrective->inspection : []; @endphp
                            {{ !empty($inspections) ? implode(', ', $inspections) : '-' }}
                        </td>
                        <td class="px-6 py-4">{{ $corrective->problem ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $corrective->solution ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $corrective->sparepart ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $corrective->quantity ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $corrective->inspection_result ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @php $technicians = is_array($corrective->technician) ? $corrective->technician : []; @endphp
                            {{ !empty($technicians) ? implode(', ', $technicians) : '-' }}
                        </td>
                        <td class="px-6 py-4">{{ $corrective->user_name ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-4">
                                <a href="{{ route('correctives.show', $corrective) }}" class="text-blue-600 hover:underline">View</a>
                                <a href="{{ route('correctives.edit', $corrective) }}" class="text-emerald-700 hover:underline">Edit</a>
                                <form action="{{ route('correctives.destroy', $corrective) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this corrective maintenance report?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="12" class="py-10 text-center text-slate-500">No corrective data available.</td></tr>
                @endforelse
            </x-table>
        </div>

        <!-- =================== PER RUANGAN VIEW =================== -->
        @php
            $itemsToGroup  = method_exists($correctives, 'getCollection') ? $correctives->getCollection() : $correctives;
            $groupedItems  = $itemsToGroup->groupBy(function ($item) {
                return !empty(trim((string)$item->room)) ? trim((string)$item->room) : 'Unassigned / Ruangan Tidak Ditentukan';
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
            @forelse($groupedItems as $roomName => $roomItems)
                <div id="room-{{ Str::slug($roomName) }}" x-data="{ isOpen: true }" class="relative rounded-[28px] border border-white/[0.35] bg-white/[0.08] shadow-[0_12px_28px_rgba(15,23,42,0.06)] backdrop-blur-[30px] overflow-hidden transition scroll-mt-24">

                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent opacity-50 pointer-events-none"></div>

                    <!-- Room Accordion Header -->
                    <button
                        type="button"
                        @click="isOpen = !isOpen"
                        class="relative z-10 w-full flex items-center justify-between px-6 py-5 bg-white/[0.02] hover:bg-white/[0.06] transition text-left border-b border-white/[0.15]">

                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center">
                                <svg viewBox="0 0 24 24" class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M3 21h18M3 7v14M21 7v14M6 21V3h12v18M9 6h2M9 10h2M9 14h2M13 6h2M13 10h2M13 14h2"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-slate-900">{{ $roomName }}</h2>
                                <p class="text-xs text-slate-500">Corrective Maintenance Records</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="rounded-full border border-amber-200 bg-amber-50 px-3.5 py-1 text-xs font-bold text-amber-700 shadow-sm">
                                {{ $roomItems->count() }} {{ Str::plural('Record', $roomItems->count()) }}
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
                                    <th class="px-6 py-3.5 text-left">Tanggal</th>
                                    <th class="px-6 py-3.5 text-left">Distributor</th>
                                    <th class="px-6 py-3.5 text-left">Pemeriksaan</th>
                                    <th class="px-6 py-3.5 text-left">Problem / Diagnosa</th>
                                    <th class="px-6 py-3.5 text-left">Solution</th>
                                    <th class="px-6 py-3.5 text-left">Sparepart</th>
                                    <th class="px-6 py-3.5 text-left">Jumlah</th>
                                    <th class="px-6 py-3.5 text-left">Hasil Pemeriksaan</th>
                                    <th class="px-6 py-3.5 text-left">Teknisi</th>
                                    <th class="px-6 py-3.5 text-left">User</th>
                                    <th class="px-6 py-3.5 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @foreach($roomItems as $index => $corrective)
                                    <tr class="bg-white hover:bg-slate-50 transition text-slate-900">
                                        <td class="px-6 py-4 text-slate-500">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">{{ $corrective->repair_date ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $corrective->distributor ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            @php $ins = is_array($corrective->inspection) ? $corrective->inspection : []; @endphp
                                            {{ !empty($ins) ? implode(', ', $ins) : '-' }}
                                        </td>
                                        <td class="px-6 py-4">{{ Str::limit($corrective->problem, 50) ?: '-' }}</td>
                                        <td class="px-6 py-4">{{ Str::limit($corrective->solution, 50) ?: '-' }}</td>
                                        <td class="px-6 py-4">{{ $corrective->sparepart ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $corrective->quantity ?? '-' }}</td>
                                        <td class="px-6 py-4">{{ $corrective->inspection_result ?? '-' }}</td>
                                        <td class="px-6 py-4">
                                            @php $techs = is_array($corrective->technician) ? $corrective->technician : []; @endphp
                                            {{ !empty($techs) ? implode(', ', $techs) : '-' }}
                                        </td>
                                        <td class="px-6 py-4">{{ $corrective->user_name ?? '-' }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-4">
                                                <a href="{{ route('correctives.show', $corrective) }}" class="text-blue-600 hover:underline">View</a>
                                                <a href="{{ route('correctives.edit', $corrective) }}" class="text-emerald-700 hover:underline">Edit</a>
                                                <form action="{{ route('correctives.destroy', $corrective) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this corrective maintenance report?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                                </form>
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
                    No corrective data available for grouping.
                </div>
            @endforelse

        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $correctives->links() }}
        </div>

    </div>

</x-app-layout>